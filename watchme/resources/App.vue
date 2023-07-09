<template>
<div class="grid-container">
    <div class="grid-container__menu">
        <Timer :TimerDuration="watertimerduration"
               :TimerActivated="watertimer"
               :display-image="imagedrinktimer"
               @click="activateWaterTimer"
               @expired="handleTimerExpiredwater('Water')"
        ></Timer>
        <Timer :TimerDuration="standtimerduration"
               :TimerActivated="standtimer"
               :display-image="imagestandtimer"
               @click="activateStandTimer"
               @expired="handleTimerExpiredstanding('Standing')"
        ></Timer>
    </div>
    <div class="grid-container__main" >
        <Stoppuhr></Stoppuhr>
        <button @click="readTimer">ReadTimer</button>
    </div>
</div>


</template>

<script>
import Timer from "./components/Timer.vue";
import Stoppuhr from "./components/Stoppuhr.vue";
import activedrinkimage from "./assests/waterbottle_activated.png";
import disableddrinkimage from "./assests/waterbottle_deactivated.png";
import alertsound from "./assests/alert-bell.wav";
import activestandimage from "./assests/standup_activated.png";
import disabledstandimage from "./assests/standup_deactivated.png";
export default {
    name: "app",
    data(){
        return {
            title: 'App',
            imagedrinktimer: disableddrinkimage,
            imagestandtimer: disabledstandimage,
            watertimer: false,
            standtimer: false,
            watertimerduration: 20,
            standtimerduration: 20,
        }
    },
    components:{
            Timer,
        Stoppuhr
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
                   // alert(xhr.responseText);
                }
            }
            xhr.send(data);
        },
        activateWaterTimer(){
            this.watertimer = !this.watertimer;
            if(this.imagedrinktimer === disableddrinkimage) {
                this.imagedrinktimer = activedrinkimage;
            }else this.imagedrinktimer = disableddrinkimage
        },
        activateStandTimer(){
            this.standtimer = !this.standtimer;
            if(this.imagestandtimer === disabledstandimage) {
                this.imagestandtimer = activestandimage;
            }else this.imagestandtimer = disabledstandimage;
        },
        handleTimerExpiredstanding(timerType) {

            const audio = new Audio(alertsound); // Passe den Dateinamen und Pfad zu deinem Alarmton an
            audio.addEventListener('ended', function() {
                alert("Timer expired: " + timerType);
            });
            audio.play();
            // Do something with the timerType variable
        },
        handleTimerExpiredwater(timerType) {
            const audio = new Audio(alertsound); // System-Alarmton
            audio.addEventListener('ended', function() {
                alert("Timer expired: " + timerType);
            });
            audio.play();

        // Do something with the timerType variable
    },

    }
}
</script>

<style>
    .grid-container{
        display: flex;
        justify-content: center;

    }
    .grid-container__menu{
        display: flex;
        background: blue;
        flex-direction: column;

    }
    .grid-container__main{
        background: red;
    }
</style>
