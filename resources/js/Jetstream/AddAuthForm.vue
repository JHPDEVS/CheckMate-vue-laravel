<template>
    <div class="flex justify-center items-center h-screen w-full bg:white">
    <div class="w-1/2 bg-white rounded shadow-2xl p-8 m-4">
        <form action="/setInfo/save" method="post">
             
                    <div class="flex flex-col mb-4 ">
                <label class="mb-2 font-bold text-lg text-gray-900" for="email">이메일</label>
                <input class="border py-2 px-3 text-grey-800 md:w-1/2" type="email" name="email" id="email">

            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="phone_number">전화번호</label>
                <input class="border py-2 px-3 text-grey-800" type="text" name="phone_number" id="phone_number">
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="last_name">반</label>
                <label><input type="radio" name="class" value="WDJ"> WDJ</label>
                 <label><input type="radio" name="class" value="CPJ"> CPJ</label>
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="sid">학번</label>
                <input class="border py-2 px-3 text-grey-800" type="number" name="sid" id="sid">
            </div>
            <div class="flex flex-col mb-4">
                <label class="mb-2 font-bold text-lg text-gray-900" for="password">직업</label>
                 <label><input type="radio" name="position" value="student"> 학생</label>
                 <label><input type="radio" name="position" value="professor"> 교수</label>
            </div>
            <button class="block bg-teal-400 hover:bg-teal-600 text-white uppercase text-lg mx-auto p-4 rounded" type="submit">Create Account</button>
                  <input type="submit" class="btn btn-primary" value="제출">  
                   <input type="hidden" name="_token" :value="csrf">
        </form>
    </div>
</div>

</template>

<script>
import Input from './Input.vue'

    export default {
        data: function() {
            return {
                msg:'',
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
            }
        },
        components: {
     
                Input   },
    }
</script>
