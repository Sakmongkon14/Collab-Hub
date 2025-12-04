@extends('layouts.Tailwind')
@section('title','หน้าแรกของเว็บไซต์')
@section('content')
<style>
    *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: sans-serif;

}

.bgImage{
    position: absolute;
    width: 100%;
    height: 88%;
    background-image: url("https://img.pikbest.com/wp/202347/pastel-green-background-3d-rendering-of-a-winner-s-podium-on-matching_9767186.jpg!bw700");
    background-size: 100%;
    background-repeat: on-repeat;
    background-position: center bottom;



}
   


    .bgImage::after{
        content:'';
        position: absolute;
        widows: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.55)
    }
    .content{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .content h2 {

        font-size: 50px;
        color: rgb(7, 7, 7);
        margin-bottom: 20px
        opacity: 0;
        animation: slideTop 1s ease forwards;
        animation-delay: .1s;
    }

    .content h2:nth-of-type(2) {
    margin-bottom: 30px;
    animation: slideTop 1s ease forwards;
    animation-delay: .7s;
}
    .content h2 span{
        color: #127072
    }



    /* กำหนดแอนิเมชัน slideTop */

@keyframes slideTop {
    0% {
        transform: translateY(100px); /* เริ่มจากด้านซ้ายสุด */
        opacity: 0; /* เริ่มต้นด้วยความโปร่งใส */
    }
    100% {
        transform: translateY(0); /* เคลื่อนที่ไปยังตำแหน่งเดิม */
        opacity: 1; /* เปลี่ยนเป็นไม่โปร่งใส */
    }
}


</style>


<body>

    <div class="bgImage">
    
</div>
    <div class="content">
        <h2>Welcome To <span>GTN</span></h2>

    </div>
<body>



@endsection