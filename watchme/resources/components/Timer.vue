<template>
    <button :class="{ 'Timer__button': TimerActivated}">
        <div class="Timer__numbers Timer__numbers--onhover">
            <span class="numbers">{{ timerValue.hours }}</span><span class="numbers">:</span>
            <span class="numbers" >{{ timerValue.minutes }}</span><span class="numbers">:</span>
            <span class="numbers">{{ timerValue.seconds }}</span>
        </div>
        <img class="Timer__image" :src="displayImage" alt="E 404" />

    </button>
</template>

<style scoped>


.Timer__button:hover .Timer__numbers--onhover {
    display: flex;
    align-content: center;
    justify-content: center;
    width: 50px;
    height: 50px;

}
.Timer__button:hover .Timer__image {
    display: none;
}
.Timer__button:not(:hover) .Timer__image {
    display: block;
}

.Timer__numbers {
    display: none;
}
.numbers{
    display: flex;
    align-items: center;
    justify-content: center;
}
.Timer__image{
    max-width: 50px;
    max-height: 50px;
}

</style>


<script>
import { watchEffect, onMounted, reactive, getCurrentInstance } from "vue";
import { useTimer } from 'vue-timer-hook';

export default {
    name: "Timer",
    props: {
        displayImage: {
            type: String,
            default: "",
        },
        TimerActivated: {
            type: Boolean,
            default: false
        },
        TimerDuration:{
            type: Number,
            default: 100
        },
    },
    emits: ['expired'],
    watch: {
        TimerActivated(newValue) {
            if (newValue) {
                this.restartfunction(this.TimerDuration);
            }else{
                this.timer.pause();
            }
        },
    },
    setup(props) {

        const {emit} = getCurrentInstance();
        const time = new Date();
        time.setSeconds(time.getSeconds() + 1000000);
        const timer = useTimer(time);
        const timerValue = reactive({
            hours: timer.hours,
            minutes: timer.minutes,
            seconds: timer.seconds,
            isRunning: timer.isRunning,
            expired: timer.isExpired
        });
        const restartfunction = (duration) => {
            const time = new Date();
            time.setSeconds(time.getSeconds() + duration );
            timer.restart(time);
        };

        onMounted(() => {
            watchEffect(async () => {
                if(timer.isExpired.value) {
                    emit('expired', true);
                    restartfunction(props.TimerDuration);
                }
            })
        })

        return {
            timer,
            timerValue,
            restartfunction,
        };
    },
};
</script>

