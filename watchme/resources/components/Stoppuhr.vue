<template>
    <div class="Stoppuhr-container">
        <div class="Stoppuhr">
            <div class ="Stoppuhr__Stoppuhr-numbers">
                <span class="Stoppuhr-numbers__hours">{{stopwatch.hours}}</span>:<span class="Stoppuhr-numbers__min">{{stopwatch.minutes}}</span>:<span class="Stoppuhr-numbers__sec">{{stopwatch.seconds}}</span>
            </div>
            <div class="Stoppuhr__button-container">
                <button class="button-container__togglewatch" @click="togglewatch"><img :src="start_stop_png" alt="play button"/></button>
                <button class="button-container__resetwatch" @click="resetwatch"><img :src="stoppng" alt="stop button"/></button>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { useStopwatch } from 'vue-timer-hook'; // https://github.com/riderx/vue-timer-hook
import start_png from "../assests/icon_play.png";   // https://icons8.com
import pause_png from "../assests/icon_pause.png";  // https://icons8.com
import stopp_png from "../assests/icon_stop.png";   // https://icons8.com

export default {
    name: "Stoppuhr",
    data(){
        return {
            stoppng: stopp_png,
            stopwatch: useStopwatch(0,false),
            test: true
        }
    },computed: {
        start_stop_png() {
            return this.stopwatch.isRunning ? pause_png : start_png;
        }
    },
    methods:{
        togglewatch(){
            this.stopwatch.isRunning ? this.stopwatch.pause() : this.stopwatch.start();
        },
        resetwatch(){
            this.stopwatch.reset();
            this.stopwatch.pause();
        },
    }

}
</script>

<style>
.Stoppuhr-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.Stoppuhr {
    text-align: center;
}

.Stoppuhr__Stoppuhr-numbers{
    font-size: 150px;
}

.Stoppuhr__button-container button{
    margin: 20px;
    border-radius: 10px;
}
</style>
