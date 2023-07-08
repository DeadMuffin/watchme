<template>
    <div class="Timer-container">
        <div class="Timer">
            <div class ="Timer__Timer-numbers">
                <span class="Timer-numbers__hours">{{stopwatch.hours}}</span>:<span class="Timer-numbers__min">{{stopwatch.minutes}}</span>:<span class="Timer-numbers__sec">{{stopwatch.seconds}}</span>
            </div>
            <div class="Timer__button-container">
                <button class="button-container__togglewatch" @click="togglewatch"><img :src="start_stop_png" alt="play button"/></button>
                <button class="button-container__resetwatch" @click="resetwatch"><img :src="stoppng" alt="stop button"/></button>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import { useStopwatch } from 'vue-timer-hook';
import start_png from "../assests/icon_play.png";
import pause_png from "../assests/icon_pause.png";
import stopp_png from "../assests/icon_stop.png";

export default {
    name: "Timer",
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
.Timer-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.Timer {
    text-align: center;
}

.Timer__Timer-numbers{
    font-size: 150px;
}

.Timer__button-container button{
    margin: 20px;
    border-radius: 10px;
}
</style>
