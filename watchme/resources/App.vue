<template>

    <button @click="readTimer">ReadTimer</button>
    <Timer></Timer>

</template>

<script>
import Timer from "./components/Timer.vue";

export default {
    name: "app",
    data(){
        return {
            title: 'App'
        }
    },
    components:{
            Timer
        },
    methods:{
        readTimer(){
            let time = document.querySelectorAll('[class*="Timer-numbers__"]');
           // alert(time.item(0).textContent + " : " + time.item(1).textContent + " : " + time.item(2).textContent);
            let duration = ((((parseInt(time.item(0).textContent * 60)) + parseInt(time.item(1).textContent)) * 60) + parseInt(time.item(2).textContent));
            let comment = "Ich bims ein Kommentar!";


            let data = new FormData();
            data.append('duration',duration);
            data.append('comment',comment);

            let xhr = new XMLHttpRequest();
            xhr.open('POST','/api/projects/times');
            xhr.onreadystatechange = function (){
                if(xhr.readyState === 4){
                    alert(xhr.responseText);
                }
            }
            xhr.send(data);
        }
    }
}
</script>

<style>

</style>
