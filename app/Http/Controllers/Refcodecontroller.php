<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Refcodecontroller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if ($request->has('export')) {
            $rows     = DB::table('r_import_refcode')->get();
            $filePath = storage_path('app/refcode.csv');
            $file     = fopen($filePath, 'w');

            // 🔥 ใส่ BOM เพื่อป้องกันภาษาไทยเพี้ยน
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // เขียนหัวตาราง
            fputcsv($file, ["Refcode", "Sitecode", "Office", "Project"]);

            // เขียนข้อมูลลงไฟล์
            foreach ($rows as $row) {
                fputcsv($file, [
                    $row->refcode,
                    $row->sitecode,
                    $row->office,
                    $row->project,
                ]);
            }

            fclose($file);
            return response()->download($filePath);
        }

        // ดึงข้อมูล 50 รายการแรกจากฐานข้อมูล
        $importrefcode = DB::table('r_import_refcode')->limit(50)->get();

        // เช็คจำนวน Refcode
        $recordCount = DB::table('r_import_refcode')->count('ref_code');

        return view('ERP.refcode.home', compact('importrefcode', 'recordCount'));
    }

    public function searchRefcode(Request $request)
    {
        $refcodeQuery = DB::table('r_import_refcode');

        // ตรวจสอบว่ามีค่าค้นหาในแต่ละช่องหรือไม่
        if ($request->filled('project_name')) {
            $refcodeQuery->where('project_name', 'like', '%' . $request->input('project_name') . '%');
        }
        if ($request->filled('ref_code')) {
            $refcodeQuery->where('ref_code', 'like', '%' . $request->input('ref_code') . '%');
        }
        if ($request->filled('group_group')) {
            $refcodeQuery->where('group_group', 'like', '%' . $request->input('group_group') . '%');
        }
        

        // ถ้าทุกช่องว่าง ให้ดึง 50 รายการแรก
        if (! $request->hasAny(['project_name', 'ref_code', 'group_group'])) {
            $refcodeQuery->limit(50);
        }

        $refcode = $refcodeQuery->get();

        return response()->json($refcode);
    }

    
    
    public function importrefcode(Request $request)
{
    $request->validate([
        'xlsx_file_add' => 'required|file|mimes:xlsx|max:40960', // 20 MB
    ], [
        'xlsx_file_add.required' => 'กรุณาเลือกไฟล์ Excel',
        'xlsx_file_add.mimes'    => 'ไฟล์ต้องเป็น .xlsx เท่านั้น',
        'xlsx_file_add.max'      => 'ไฟล์ต้องไม่เกิน 40MB',
    ]);

    ini_set('max_execution_time', 300); // 5 นาที

    // โหลดไฟล์
    $file = $request->file('xlsx_file_add')->getRealPath();
    $zip  = new \ZipArchive;

    if ($zip->open($file) !== true) {
        return back()->withErrors(['message' => 'เปิดไฟล์ Excel ไม่ได้']);
    }

    // อ่าน sharedStrings.xml
    $sharedStrings = [];
    if (($xml = $zip->getStream('xl/sharedStrings.xml'))) {
        $reader = new \XMLReader();
        $reader->XML(stream_get_contents($xml));
        while ($reader->read()) {
            if ($reader->nodeType == \XMLReader::ELEMENT && $reader->name === 't') {
                $reader->read();
                $sharedStrings[] = $reader->value ?? '';
            }
        }
        $reader->close();
    }

    // อ่าน sheet1.xml
    if (! ($xml = $zip->getStream('xl/worksheets/sheet1.xml'))) {
        return back()->withErrors(['message' => 'ไม่พบ sheet1']);
    }

    $reader = new \XMLReader();
    $reader->XML(stream_get_contents($xml));

    $skipHeader = true;
    $currentRow = [];
    $dataToInsert = [];
    $batchSize = 200; // batch size ปลอดภัยสำหรับ MySQL
    $rowCount = 0;

    // truncate table ก่อน import
    DB::table('r_import_refcode')->truncate();

    while ($reader->read()) {
        if ($reader->nodeType == \XMLReader::ELEMENT && $reader->name === 'row') {
            $currentRow = [];
        }

        if ($reader->nodeType == \XMLReader::ELEMENT && $reader->name === 'c') {
            $cellRef = $reader->getAttribute('r');
            preg_match('/[A-Z]+/', $cellRef, $colLetters);
            $colIndex = $this->excelColumnToIndex($colLetters[0]);
            $type     = $reader->getAttribute('t');

            $reader->read();
            if ($reader->nodeType == \XMLReader::ELEMENT && $reader->name === 'v') {
                $reader->read();
                $value = $reader->value ?? null;
                if ($type === 's') {
                    $value = $sharedStrings[(int) $value] ?? null;
                }
                $currentRow[$colIndex] = $value;
            }
        }

        if ($reader->nodeType == \XMLReader::END_ELEMENT && $reader->name === 'row') {
            if ($skipHeader) {
                $skipHeader = false;
                continue;
            }

            if (empty($currentRow)) continue;

            // เตรียมช่องว่างให้ครบ 66 คอลัมน์ (0–65)
            for ($i=0; $i<=65; $i++) {
                $currentRow[$i] = $currentRow[$i] ?? null;
            }

            // Mapping row
            $mappedRow = [
                'no'                  => $currentRow[0],
                'project_no'          => $currentRow[1],
                'project_name'        => $currentRow[2],
                'ref_code'            => $currentRow[3],
                'project_type'        => $currentRow[4],
                'construction_status' => $currentRow[5],
                'active_y_n'          => $currentRow[6],
                'unit_type'           => $currentRow[7],
                'owner'               => $currentRow[8],
                'item'                => $currentRow[9],
                'currency'            => $currentRow[10],
                'budget_contract'     => $currentRow[11],
                'group_group'         => $currentRow[12],
                'control_budget'      => $currentRow[13],
                'control_boq'         => $currentRow[14],
                'project_contract'    => $currentRow[15],
                'gl_ic'               => $currentRow[16],
                'ac_ic_control'       => $currentRow[17],
                'ac_ic_secondary'     => $currentRow[18],
                'sale'                => $currentRow[19],
                'project_manager'     => $currentRow[20],
                'engineer'            => $currentRow[21],
                'project_director'    => $currentRow[22],
                'section_director'    => $currentRow[23],
                'division_director'   => $currentRow[24],
                'approve_bg'          => $currentRow[25],
                'signing_no'          => $currentRow[26],
                'amount'              => $currentRow[27],
                'proj_budget'         => $currentRow[28],
                'add_by'              => $currentRow[29],
                'add_date'            => $currentRow[30],
                'edit_by'             => $currentRow[31],
                'edit_date'           => $currentRow[32],
                'unit_re'             => $currentRow[33],
                'runproject'          => $currentRow[34],
                'hpre_event'          => $currentRow[35],
                'proc2'               => $currentRow[36],
                'proc3'               => $currentRow[37],
                'proc4'               => $currentRow[38],
                'proc5'               => $currentRow[39],
                'pre_des_s'           => $currentRow[40],
                'bank_code'           => $currentRow[41],
                'pr_empno'            => $currentRow[42],
                'projcenter'          => $currentRow[43],
                'projno'              => $currentRow[44],
                'projdate'            => $currentRow[45],
                'projyear'            => $currentRow[46],
                'totadd'              => $currentRow[47],
                'areaqty'             => $currentRow[48],
                'unitcode'            => $currentRow[49],
                'unitstatus'          => $currentRow[50],
                'revno'               => $currentRow[51],
                'salecode'            => $currentRow[52],
                'acvat'               => $currentRow[53],
                'book2_no'            => $currentRow[54],
                'book2'               => $currentRow[55],
                'pre_thi'             => $currentRow[56],
                'customer_code'       => $currentRow[57],
                'plugin'              => $currentRow[58],
                'type_code'           => $currentRow[59],
                'area'                => $currentRow[60],
                'allocate_status'     => $currentRow[61],
                'projname2'           => $currentRow[62],
                'sec_empno'           => $currentRow[63],
                'div_empno'           => $currentRow[64],
                'proj_location'       => $currentRow[65],
            ];

            $dataToInsert[] = $mappedRow;
            $rowCount++;

            if (count($dataToInsert) >= $batchSize) {
                DB::table('r_import_refcode')->insert($dataToInsert);
                $dataToInsert = [];
            }
        }
    }

    // insert batch สุดท้าย
    if (!empty($dataToInsert)) {
        DB::table('r_import_refcode')->insert($dataToInsert);
    }

    $reader->close();
    $zip->close();

    return back()->with('success', "นำเข้าข้อมูลทั้งหมจำนวน {$rowCount} เรียบร้อยแล้ว");
}



/**
 * แปลง column letter → index
 * เช่น A=0, B=1, Z=25, AA=26, AB=27 ...
 */

    private function excelColumnToIndex($letters)
    {
        $letters = str_split($letters);
        $index   = 0;
        foreach ($letters as $char) {
            $index = $index * 26 + (ord($char) - ord('A') + 1);
        }
        return $index - 1;
    }


}
