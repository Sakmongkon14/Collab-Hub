@extends('layouts.user')

@section('title', 'Project Databases - Project View')

@section('content')
    <!-- Export To Excel -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons/css/all/all.css">
    <!-- Load Font Awesome for Icons -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">

    <!-- แสดงข้อความ error -->
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- แสดงข้อความสำเร็จ -->
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#22c55e',
                    customClass: {
                        title: 'swal-title',
                        content: 'swal-text'
                    }
                });
            });
        </script>
    @endif

    <!--แปลงเป็น Editable-->

    <style>
        /* Input field แบบ Excel */
        .excel-input {
            width: 100%;
            min-width: 100px;
            /* ให้ cell แสดงเต็มความยาวที่ต้องการ */

            background: transparent;
            /* พื้นหลังใส */
            font-size: 10px;
            text-align: center;
            box-sizing: border-box;
            /* รวม padding + border ใน width */
            white-space: nowrap;
            /* อยู่บรรทัดเดียว */

            text-overflow: ellipsis;
            /* แสดง ... ถ้ายาวเกิน */
            transition: all 0.2s;
            /* effect เวลา focus */
        }

        /* Focus / editable state */
        .excel-input:focus,
        .excel-input.active-hover {
            outline: 1px solid #3b82f6;
            /* สีฟ้า */
            background: #eef6ff;
            /* ฟีล Excel */
            border-radius: 10%;
            /* มุมโค้งเล็กน้อย */
        }

        /* Readonly cell */
        .readonly-cell {
            background-color: #f5f5f5;
            /* เทาอ่อน */
            cursor: not-allowed;
        }
    </style>

    <style>
        .swal-title,
        .swal-text {
            font-family: 'Sarabun', sans-serif;
        }

        .readonly-cell {
            background-color: transparent !important;
            color: #000;
        }
    </style>

    <style>
        /* input เงิน */
        .money-input {
            text-align: right;
        }

        /* readonly แต่ไม่เอาพื้นหลังเทา */
        input[readonly] {
            background-color: transparent !important;
            border: none;
            box-shadow: none;
            cursor: default;
        }

        /* กัน disabled ทำให้สีจาง */
        input[disabled] {
            background-color: transparent !important;
            color: inherit;
            opacity: 1;
        }
    </style>

    <!-- สคริปต์จัดการ input เงิน -->
    <script>
        function parseMoney(val) {
            if (!val) return 0;
            return parseFloat(val.replace(/,/g, '')) || 0;
        }

        function formatMoney(num) {
            return num.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // จำกัดให้กรอกได้เฉพาะตัวเลข
        $(document).on('input', '.money-input', function() {
            let val = $(this).val().replace(/[^0-9.]/g, '');
            let parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts[1];
            }
            $(this).val(val);
        });

        // ใส่ comma ตอน blur
        $(document).on('blur', '.money-input', function() {
            let num = parseMoney($(this).val());
            if ($(this).val() !== '') {
                $(this).val(formatMoney(num));
            }
        });

        // คำนวณ realtime
        $(document).on('input', '.money-input', function() {
            let row = $(this).closest('tr');

            let revenue = parseMoney(row.find('[data-field="Estimated_Revenue_PJ"]').val());
            let service = parseMoney(row.find('[data-field="Estimated_Service_Cost_PJ"]').val());
            let material = parseMoney(row.find('[data-field="Estimated_Material_Cost_PJ"]').val());

            let grossProfit = revenue - service - material;
            let grossMargin = revenue !== 0 ? (grossProfit / revenue) * 100 : 0;

            row.find('.gross-profit').val(formatMoney(grossProfit));
            row.find('.gross-margin').val(formatMoney(grossMargin) + '%');
        });
    </script>











    <div class="flex h-[calc(100vh-64px)] overflow-hidden">


        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-100 overflow-y-auto">


            <div>
                <h2 class="text-center my-3 text-2xl font-bold">
                    16_TRUE Project Database
                </h2>
            </div>

            <!-- Container ปุ่ม -->
            <div class="container mx-auto mb-2">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

                    {{-- 1. ปุ่ม Export (อยู่ทางซ้าย) --}}
                    <div class="order-2 sm:order-1">
                        <button type="button" id="exportToExcel" onclick="exportToExcel()"
                            class="flex items-center px-5 py-2.5 rounded-xl font-semibold text-white text-sm
                       bg-gradient-to-r from-green-600 to-green-500
                       shadow-md hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200">
                            <i class="fas fa-file-excel mr-2 text-lg"></i>
                            Export Visible Data
                        </button>
                    </div>

                    {{-- 2. ปุ่ม Permission (อยู่ทางขวา) --}}
                    <div class="order-1 sm:order-2 flex items-center gap-3">

                        <button onclick="document.getElementById('permissionModal').classList.remove('hidden')"
                            class="group flex items-center px-6 py-2.5 bg-white text-indigo-600 border border-indigo-200 font-bold text-sm rounded-xl shadow-[0_2px_10px_-3px_rgba(79,70,229,0.2)]
           hover:bg-indigo-600 hover:text-white hover:border-indigo-600 hover:shadow-[0_10px_20px_-5px_rgba(79,70,229,0.4)] hover:-translate-y-0.5 active:scale-95 active:translate-y-0
           transition-all duration-300 ease-out">

                            {{-- Icon with animation on hover --}}
                            <i
                                class="fas fa-user-shield mr-2.5 text-base transition-transform duration-300 group-hover:rotate-12"></i>

                            <span class="tracking-wide">Permission</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Modal -->
            <form action="{{ route('permissions.save', $projectCode) }}" method="POST">
                @csrf

                <input type="hidden" name="project_code" value="{{ $projectCode }}">


                <div id="permissionModal"
                    class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white w-full max-w-[1200px] h-[80vh] rounded-xl shadow-lg overflow-hidden flex flex-col">

                        <!-- Header -->
                        <div class="flex justify-between items-center p-4 border-b border-gray-300">
                            <h6 class="text-lg font-bold">Manage Permissions</h6>
                            <button type="button"
                                onclick="document.getElementById('permissionModal').classList.add('hidden')"
                                class="text-gray-500 hover:text-gray-800">&times;</button>

                        </div>

                        <!-- Body -->
                        <div class="overflow-auto flex-1 p-3 bg-gray-50 rounded-md">
                            <div class="min-w-[6000px]">
                                <table class="w-full border-collapse border border-gray-300 text-center text-sm">
                                    <thead class="bg-blue-950 text-white">
                                        <tr class="h-8 text-xs">
                                            <th class="border px-2 w-[140px]">User</th>
                                            <th class="border px-2 w-[100px]">Project Member</th>
                                            <th class="border px-2 w-[140px]">Project Role</th>

                                            <th class="border px-2 w-[140px]" style="background-color: green">Customer
                                                Region</th>
                                            <th class="border px-2 w-[140px]" style="background-color: green">Estimated
                                                Revenue</th>
                                            <th class="border px-2 w-[140px]" style="background-color: green">Estimated
                                                Service Cost</th>
                                            <th class="border px-2 w-[140px]" style="background-color: green">Estimated
                                                Material Cost</th>

                                            <!-- Read / invisible   -->
                                            <th class="border px-2 w-[140px]" style="background-color: blue">Estimated Gross
                                                Profit</th>
                                            <th class="border px-2 w-[140px]" style="background-color: blue">Estimated Gross
                                                Profit Margin</th>

                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col1</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col2</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col3</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col4</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col5</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col6</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col7</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col8</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col9</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col10</th>

                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col11</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col12</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col13</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col14</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col15</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col16</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col17</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col18</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col19</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col20</th>

                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col21</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col22</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col23</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col24</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col25</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col26</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col27</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col28</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col29</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col30</th>

                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col31</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col32</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col33</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col34</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col35</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col36</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col37</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col38</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col39</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col40</th>

                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col41</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col42</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col43</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col44</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col45</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col46</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col47</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col48</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col49</th>
                                            <th class="border px-2 w-[100px] bg-red-400 text-white">Col50</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr
                                                class="h-8 text-xs odd:bg-white even:bg-gray-100 hover:bg-blue-50 transition">

                                                <!-- User -->
                                                <td class="border px-2 font-semibold text-gray-800">
                                                    {{ $user->name }}
                                                </td>

                                                <!-- Project Member -->
                                                <td class="border px-2">
                                                    <select name="member_status[{{ $user->id }}]"
                                                        class="text-xs p-1 border rounded w-full bg-white">


                                                        <option value="no"
                                                            {{ isset($permissions[$user->id]) && $permissions[$user->id]->member_status === 'no' ? 'selected' : '' }}>
                                                            No
                                                        </option>

                                                        <option value="yes"
                                                            {{ isset($permissions[$user->id]) && $permissions[$user->id]->member_status === 'yes' ? 'selected' : '' }}>
                                                            Yes
                                                        </option>

                                                    </select>
                                                </td>


                                                <!-- Project Role -->
                                                <td class="border px-2">
                                                    <select name="project_role[{{ $user->id }}]"
                                                        class="text-xs p-1 border rounded w-full bg-white hover:bg-gray-50 font-medium project-role"
                                                        data-user-id="{{ $user->id }}">

                                                        <!-- ค่าเริ่มต้น No -->
                                                        <option value=""
                                                            {{ !isset($permissions[$user->id]) || $permissions[$user->id]->project_role === null ? 'selected' : '' }}>
                                                            No
                                                        </option>

                                                        <option value="Project Manager"
                                                            {{ isset($permissions[$user->id]) && $permissions[$user->id]->project_role === 'Project Manager'
                                                                ? 'selected'
                                                                : '' }}>
                                                            Project Manager
                                                        </option>

                                                        <option value="Project Admin"
                                                            {{ isset($permissions[$user->id]) && $permissions[$user->id]->project_role === 'Project Admin'
                                                                ? 'selected'
                                                                : '' }}>
                                                            Project Admin
                                                        </option>

                                                        <option value="Site Supervisor"
                                                            {{ isset($permissions[$user->id]) && $permissions[$user->id]->project_role === 'Site Supervisor'
                                                                ? 'selected'
                                                                : '' }}>
                                                            Site Supervisor
                                                        </option>

                                                        <option value="Project Director"
                                                            {{ isset($permissions[$user->id]) && $permissions[$user->id]->project_role === 'Project Director'
                                                                ? 'selected'
                                                                : '' }}>
                                                            Project Director
                                                        </option>
                                                    </select>
                                                </td>



                                                {{-- ===== Project-level permissions ===== --}}
                                                @php
                                                    $projectFields = [
                                                        'Customer_Region_PJ',
                                                        'Estimated_Revenue_PJ',
                                                        'Estimated_Service_Cost_PJ',
                                                        'Estimated_Material_Cost_PJ',

                                                        // 🔥 2 อันใหม่ (read / invisible เท่านั้น)
                                                        'Estimated_Gross_Profit_PJ',
                                                        'Estimated_Gross_Profit_Margin_PJ',
                                                    ];

                                                    // field ที่ห้าม write
                                                    $readOnlyFields = [
                                                        'Estimated_Gross_Profit_PJ',
                                                        'Estimated_Gross_Profit_Margin_PJ',
                                                    ];
                                                @endphp

                                                @foreach ($projectFields as $field)
                                                    <td class="border px-2">
                                                        <select
                                                            name="{{ $field }}_permission[{{ $user->id }}]"
                                                            class="text-xs p-1 border rounded w-full bg-white project-permission"
                                                            data-field="{{ $field }}"
                                                            data-user-id="{{ $user->id }}">

                                                            <option value="invisible"
                                                                {{ isset($permissions[$user->id]) && ($permissions[$user->id]->$field ?? 'invisible') === 'invisible' ? 'selected' : '' }}>
                                                                Invisible
                                                            </option>

                                                            <option value="read"
                                                                {{ isset($permissions[$user->id]) && ($permissions[$user->id]->$field ?? '') === 'read' ? 'selected' : '' }}>
                                                                Read
                                                            </option>

                                                            {{-- ❌ ไม่ให้ Write สำหรับ Gross --}}
                                                            @if (!in_array($field, $readOnlyFields))
                                                                <option value="write"
                                                                    {{ isset($permissions[$user->id]) && ($permissions[$user->id]->$field ?? '') === 'write' ? 'selected' : '' }}>
                                                                    Write
                                                                </option>
                                                            @endif

                                                        </select>
                                                    </td>
                                                @endforeach


                                                <!-- Dynamic Columns -->
                                                @for ($i = 1; $i <= 50; $i++)
                                                    <td class="border px-2">
                                                        <select
                                                            name="col{{ $i }}_permission[{{ $user->id }}]"
                                                            class="text-xs p-1 border rounded w-full bg-white hover:bg-gray-50 dynamic-col"
                                                            data-col="{{ $i }}"
                                                            data-user-id="{{ $user->id }}">
                                                            <option value="invisible"
                                                                {{ isset($permissions[$user->id]) && $permissions[$user->id]->{"col$i"} === 'invisible' ? 'selected' : '' }}>
                                                                Invisible</option>
                                                            <option value="read"
                                                                {{ isset($permissions[$user->id]) && $permissions[$user->id]->{"col$i"} === 'read' ? 'selected' : '' }}>
                                                                Read</option>
                                                            <option value="write"
                                                                {{ isset($permissions[$user->id]) && $permissions[$user->id]->{"col$i"} === 'write' ? 'selected' : '' }}>
                                                                Write</option>
                                                        </select>
                                                    </td>
                                                @endfor


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <!-- Footer -->
                        <div class="p-4 border-t border-gray-300 flex justify-end space-x-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Save Permissions
                            </button>
                        </div>


                    </div>
                </div>
            </form>

            <style>
                .custom-container {
                    height: 60px;
                    /* Adjust the height of the container as needed */
                }

                .fixed-width-input {
                    height: 40px;
                    /* Adjust the height of the input field */
                    width: 170px;
                }

                .btn {
                    height: 40px;
                }

                #exportButton {
                    width: 125px;
                }
            </style>


            <style>
                .input-group {
                    position: relative;
                    display: flex;
                    flex-wrap: wrap;
                    align-items: center;
                    width: 25%;
                }

                .table-container {
                    width: 98%;
                    max-height: 500px;
                    overflow-x: auto;
                    overflow-y: auto;
                }


                .table-container table {
                    height: 100%;
                    border-collapse: collapse;
                }

                .table-container td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                    white-space: nowrap;
                }

                .table-container th {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: center;
                    white-space: nowrap;
                    position: sticky;
                    top: 0px;
                    text-align: center;
                    background-color: #172554;
                    /* เทียบเท่า bg-blue-950 */
                    color: white;
                    /* เพิ่มเพื่อให้ตัวหนังสืออ่านง่าย */
                }
            </style>


            <!-- ตารางข้อมูล โปรเจค-->
            <div>

                @php
                    $projectCols = [
                        'Customer_Region_PJ' => 'Customer Region',
                        'Estimated_Revenue_PJ' => 'Estimated Revenue',
                        'Estimated_Service_Cost_PJ' => 'Estimated Service Cost',
                        'Estimated_Material_Cost_PJ' => 'Estimated Material Cost',
                    ];

                    $moneyFields = ['Estimated_Revenue_PJ', 'Estimated_Service_Cost_PJ', 'Estimated_Material_Cost_PJ'];
                @endphp


                <div class="table-container">
                    <table class="table" id="table">
                        <thead>
                            <tr style="font-size:12px; text-align:center;">
                                <th>Refcode</th>
                                <th>Site Code</th>
                                <th>Job Description</th>
                                <th>Office Code</th>

                                {{-- ===== Project Columns (thead) ===== --}}
                                @foreach ($projectCols as $field => $label)
                                    @php
                                        $visibility = $permissions[Auth::id()]->$field ?? 'invisible';
                                    @endphp

                                    <th
                                        style="
                            background:green;
                            color:white;
                            {{ $visibility === 'invisible' ? 'display:none;' : '' }}
                        ">
                                        {{ $label }}
                                    </th>
                                @endforeach


                                @php
                                    $gpVisibility = $permissions[Auth::id()]->Estimated_Gross_Profit_PJ ?? 'invisible';
                                    $gmVisibility =
                                        $permissions[Auth::id()]->Estimated_Gross_Profit_Margin_PJ ?? 'invisible';
                                @endphp

                                <th class="border px-2 w-[140px]"
                                    style="background-color: blue; color:white;
           {{ $gpVisibility === 'invisible' ? 'display:none;' : '' }}">
                                    Estimated Gross Profit
                                </th>

                                <th class="border px-2 w-[140px]"
                                    style="background-color: blue; color:white;
           {{ $gmVisibility === 'invisible' ? 'display:none;' : '' }}">
                                    Estimated Gross Profit Margin
                                </th>


                                {{-- ===== col 1–50 (thead) ===== --}}
                                @for ($i = 1; $i <= 50; $i++)
                                    @php
                                        $col = "col$i";
                                        $visibility = $permissions[Auth::id()]->$col ?? 'invisible';
                                    @endphp
                                    <th
                                        style="
                            background:red;
                            color:white;
                            {{ $visibility === 'invisible' ? 'display:none;' : '' }}
                        ">
                                        Col {{ $i }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($projectData as $item)
                                <tr style="font-size:10px; text-align:center;">

                                    <td>{{ $item->Refcode_PJ }}</td>
                                    <td>{{ $item->Site_Code_PJ }}</td>
                                    <td>{{ $item->Job_Description_PJ }}</td>
                                    <td>{{ $item->Office_Code_PJ }}</td>

                                    {{-- ===== Project Columns (tbody) ===== --}}
                                    @foreach ($projectCols as $field => $label)
                                        @php
                                            $visibility = $permissions[Auth::id()]->$field ?? 'invisible';
                                            $isRead = $visibility === 'read';
                                            $isInvisible = $visibility === 'invisible';
                                            $isMoney = in_array($field, $moneyFields);
                                            $originalValue = $item->$field ?? '';
                                        @endphp
                                        <td class="project-col {{ $field }}"
                                            style="{{ $isInvisible ? 'display:none;' : '' }}">
                                            <input type="text"
                                                class="excel-input {{ $isMoney ? 'money-input text-end' : '' }} {{ $isRead ? 'readonly-cell' : '' }}"
                                                value="{{ $isMoney
                                                    ? ($originalValue !== null && $originalValue !== ''
                                                        ? number_format((float) str_replace(',', '', $originalValue), 2)
                                                        : '')
                                                    : $originalValue }}"
                                                data-id="{{ $item->Refcode_PJ }}" data-field="{{ $field }}"
                                                data-original="{{ $originalValue }}"
                                                {{ $isRead ? 'readonly disabled tabindex=-1' : '' }}
                                                @if ($field === 'Estimated_Revenue_PJ') oninput="validateRevenue(this)" @endif>
                                        </td>
                                    @endforeach


                                    <!-- Gross Profit and Margin -->
                                    @php
                                        $revenue = (float) str_replace(',', '', $item->Estimated_Revenue_PJ ?? 0);
                                        $service = (float) str_replace(',', '', $item->Estimated_Service_Cost_PJ ?? 0);
                                        $material = (float) str_replace(
                                            ',',
                                            '',
                                            $item->Estimated_Material_Cost_PJ ?? 0,
                                        );

                                        $grossProfit = $revenue - $service - $material;
                                        $grossMargin = $revenue != 0 ? ($grossProfit / $revenue) * 100 : 0;
                                    @endphp

                                    <td style="{{ $gpVisibility === 'invisible' ? 'display:none;' : '' }}">
                                        <input type="text" class="excel-input gross-profit text-end readonly-cell"
                                            value="{{ number_format($grossProfit, 2) }}" readonly disabled
                                            tabindex="-1">
                                    </td>



                                    <td style="{{ $gmVisibility === 'invisible' ? 'display:none;' : '' }}">
                                        <input type="text" class="excel-input gross-margin text-end readonly-cell"
                                            value="{{ number_format($grossMargin, 2) }}%" readonly disabled
                                            tabindex="-1">
                                    </td>





                                    {{-- ===== col 1–50 (tbody) ===== --}}
                                    @for ($i = 1; $i <= 50; $i++)
                                        @php
                                            $col = "col$i";
                                            $visibility = $permissions[Auth::id()]->$col ?? 'invisible';
                                            $isRead = $visibility === 'read';
                                            $isInvisible = $visibility === 'invisible';
                                        @endphp

                                        <td class="col-{{ $i }}"
                                            style="{{ $isInvisible ? 'display:none;' : '' }}">
                                            <input type="text"
                                                class="excel-input {{ $isRead ? 'readonly-cell' : '' }}"
                                                value="{{ $item->$col }}" data-id="{{ $item->Refcode_PJ }}"
                                                data-field="{{ $col }}"
                                                {{ $isRead ? 'readonly disabled tabindex=-1' : '' }}>
                                        </td>
                                    @endfor

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>


        </main>
    </div>

    <script>
        function storeOriginal(input) {
            input.dataset.original = input.value;
        }

        function validateRevenue(input) {
            let raw = input.value.replace(/,/g, '').trim();

            // ❌ ว่าง
            if (raw === '') {
                input.value = input.dataset.original ?? '';
                return;
            }

            // ❌ ไม่ใช่ตัวเลข
            if (isNaN(raw)) {
                input.value = input.dataset.original ?? '';
                return;
            }

            let num = parseFloat(raw);

            // ❌ ห้ามเป็น 0
            if (num === 0) {
                input.value = input.dataset.original ?? '';
                return;
            }

            // ✔ ถูกต้อง → อัปเดต original
            input.dataset.original = input.value;
        }
    </script>




    <!-- Auto Set Permission ตาม Role -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const readOnlyProjectFields = [
                'Estimated_Gross_Profit_PJ',
                'Estimated_Gross_Profit_Margin_PJ'
            ];

            const rolePermissions = {
                "": { // No
                    cols: {
                        invisible: "all"
                    },
                    project: {
                        normal: "invisible",
                        readonly: "invisible"
                    }
                },

                "Project Manager": {
                    cols: {
                        write: "all"
                    },
                    project: {
                        normal: "write",
                        readonly: "read" // 🔥 2 ช่อง Gross = read
                    }
                },

                "Project Admin": {
                    cols: {
                        write: [1, 2]
                    },
                    project: {
                        normal: "write",
                        readonly: "read"
                    }
                },

                "Site Supervisor": {
                    cols: {
                        read: "all"
                    },
                    project: {
                        normal: "read",
                        readonly: "read"
                    }
                },

                "Project Director": {
                    cols: {
                        read: "all"
                    },
                    project: {
                        normal: "read",
                        readonly: "read"
                    }
                }
            };

            document.querySelectorAll('.project-role').forEach(roleSelect => {

                roleSelect.addEventListener('change', function() {

                    const role = this.value;
                    const row = this.closest('tr');
                    const config = rolePermissions[role] || rolePermissions[""];

                    /* ===== col 1–50 ===== */
                    row.querySelectorAll('.dynamic-col').forEach(select => {
                        const col = parseInt(select.dataset.col);

                        select.value = 'invisible'; // reset

                        if (config.cols.write === "all") select.value = 'write';
                        else if (config.cols.read === "all") select.value = 'read';
                        else if (config.cols.write?.includes(col)) select.value = 'write';
                        else if (config.cols.read?.includes(col)) select.value = 'read';
                    });

                    /* ===== 4 Project Columns ===== */
                    row.querySelectorAll('.project-permission').forEach(select => {
                        const field = select.dataset.field;

                        if (readOnlyProjectFields.includes(field)) {
                            select.value = config.project.readonly;
                        } else {
                            select.value = config.project.normal;
                        }
                    });
                });
            });
        });

        document.querySelectorAll("select").forEach(select => {
            select.addEventListener("change", function() {

                const match = this.className.match(/perm-col-(\d+)/);
                if (!match) return;

                const col = match[1];
                const mode = this.value;

                const tds = document.querySelectorAll(".col-" + col);

                tds.forEach(td => {
                    const input = td.querySelector('.excel-input');
                    if (!input) return;

                    if (mode === "invisible") {
                        td.style.display = "none";
                    } else if (mode === "read") {
                        td.style.display = "";
                        input.readOnly = true;
                        input.style.backgroundColor = "#f8f8f8";
                    } else if (mode === "write") {
                        td.style.display = "";
                        input.readOnly = false;
                        input.style.backgroundColor = "transparent";
                    }
                });
            });
        });
    </script>


    <!--แปลงเป็น Editable-->

    <style>
        /* Input field แบบ Excel */
        .excel-input {
            width: 100%;
            min-width: 100px;
            /* ให้ cell แสดงเต็มความยาวที่ต้องการ */

            background: transparent;
            /* พื้นหลังใส */
            font-size: 10px;
            text-align: center;
            box-sizing: border-box;
            /* รวม padding + border ใน width */
            white-space: nowrap;
            /* อยู่บรรทัดเดียว */

            text-overflow: ellipsis;
            /* แสดง ... ถ้ายาวเกิน */
            transition: all 0.2s;
            /* effect เวลา focus */
        }

        /* Focus / editable state */
        .excel-input:focus,
        .excel-input.active-hover {
            outline: 1px solid #3b82f6;
            /* สีฟ้า */
            background: #eef6ff;
            /* ฟีล Excel */
            border-radius: 10%;
            /* มุมโค้งเล็กน้อย */
        }

        /* Readonly cell */
        .readonly-cell {
            background-color: #f5f5f5;
            /* เทาอ่อน */
            cursor: not-allowed;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.excel-input').forEach(input => {
                input.addEventListener('blur', function() {

                    // ถ้า readonly หรือ disabled ไม่ต้อง save
                    if (this.readOnly || this.disabled) return;

                    // ส่งค่า null ถ้าเป็น empty
                    let value = this.value.trim();
                    if (value === '') value = null;

                    fetch("{{ route('newjob.inlineUpdate') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id: this.dataset.id,
                                field: this.dataset.field,
                                value: value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.classList.add('bg-green-100');
                                setTimeout(() => this.classList.remove('bg-green-100'), 500);
                            } else {
                                console.warn(data.message ?? 'Update failed');
                                // แทน alert ให้ขึ้น console แทน
                            }
                        })
                        .catch(err => {
                            console.error(err);
                        });

                });
            });
        });
    </script>


    <!-- Export -->
    <script>
        function exportToExcel() {
            const table = document.getElementById("table");
            const rows = table.querySelectorAll("tr");

            let data = [];

            rows.forEach(row => {
                let rowData = [];

                const cells = row.querySelectorAll("th, td");

                cells.forEach(cell => {
                    // ข้าม cell ที่ถูกซ่อน
                    if (cell.offsetParent === null) return;

                    let value = "";

                    // ถ้ามี input ให้ดึง value
                    const input = cell.querySelector("input");
                    if (input) {
                        value = input.value;
                    } else {
                        value = cell.innerText.trim();
                    }

                    rowData.push(value);
                });

                if (rowData.length > 0) {
                    data.push(rowData);
                }
            });

            // สร้าง workbook
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);

            XLSX.utils.book_append_sheet(wb, ws, "Visible Data");

            // export
            XLSX.writeFile(wb, "project_visible_data.xlsx");
        }
    </script>











@endsection
