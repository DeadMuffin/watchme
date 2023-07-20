<template>
<div class="grid-container">
    <div class="grid-container__menu">
        <button class="menu__settings" @click="openSettings">
            <img :src="imagesettings" alt="settings">
        </button>
        <button class="menu__analytics" @click="toggleAnalytics">
            <img :src="imageanalytics" alt="analytics" v-if="!showanalytics">
            <img :src="imagestopwatch" alt="stopwatch" v-if="showanalytics">
        </button>
        <Timer  :TimerDuration="watertimerduration*60"
               :TimerActivated="watertimer"
               :display-image="imagedrinktimer"
               @click="activateWaterTimer"
               @expired="handleTimerExpiredwater('Water')"
        ></Timer>
        <Timer  :TimerDuration="standtimerduration*60"
               :TimerActivated="standtimer"
               :display-image="imagestandtimer"
               @click="activateStandTimer"
               @expired="handleTimerExpiredstanding('Standing')"
        ></Timer>
    </div>
    <div class="grid-container__main" v-show="!showanalytics">

        <select class="main__select">
            <option disabled selected>Select Project</option>
            <option v-for="project in projects">
            {{project.name}}
            </option>
        </select>
        <NewButton class="main__add" :projects="projects" @newproject="handleNewProject"></NewButton>
        <Stoppuhr class="main__stopwatch" :reset="resetwatch"></Stoppuhr>
        <textarea class="main__comment" rows="5" placeholder="Enter your comment here!"></textarea>
        <button class="main__button" @click="readTimer">{{buttonmsg}}</button>

    </div >
    <div class="grid-container__analytics" v-show="showanalytics">
        <Analytics :update="showanalytics" :projects="projects"></Analytics>
    </div>
    <div class="overlay" v-show="showSettings">
        <!-- Overlay-Inhalt -->
        <div class="overlay-content">
            <span class="close-button" @click="closeSettings">X</span>
            <h2>Settings</h2>
            <div class="slider-container">
                <label for="slider1">Water Timer duration:</label>
                <input type="range" id="slider1" min="1" max="120" :value="watertimerduration" @input="updateWaterTimerDuration">
                <span class="slider-value">{{watertimerduration}} min</span>
            </div>
            <div class="slider-container">
                <label for="slider2">Stand Timer duration:</label>
                <input type="range" id="slider2" min="1" max="120" :value="standtimerduration" @input="updateStandTimerDuration">
                <span class="slider-value">{{standtimerduration}} min</span>
            </div>
        </div>
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
import settingsimage from "./assests/icons_settings.png";
import analyticsimage from "./assests/icons_analytics.png";
import NewButton from "./components/NewButton.vue";
import Analytics from "./components/Analytics.vue";
import imagestopwatch from "./assests/icon_stoppwatch.png";
export default {
    name: "app",
    created() {
        this.loadprojects();
    },
    data(){
        return {
            title: 'App',
            projects: [],
            classname: "main__add",
            imagedrinktimer: disableddrinkimage,
            imagestandtimer: disabledstandimage,
            imagesettings: settingsimage,
            imageanalytics: analyticsimage,
            imagestopwatch: imagestopwatch,
            watertimer: false,
            standtimer: false,
            watertimerduration: 20,
            standtimerduration: 20,
            showSettings: false,
            buttonmsg: "Submit",
            resetwatch: false,
            showanalytics: true,
        }
    },
    components:{
        Analytics,
        NewButton,
            Timer,
        Stoppuhr
        },
    methods:{
        toggleAnalytics(){
            this.showanalytics = !this.showanalytics;
        },
        updateWaterTimerDuration(event) {
            this.watertimerduration = parseInt(event.target.value);
        },
        updateStandTimerDuration(event) {
            this.standtimerduration = parseInt(event.target.value);
        },
        openSettings() {
            this.showSettings = true;
        },
        closeSettings() {
            this.showSettings = false;
        },
        handleNewProject(projectname){
            this.loadprojects();
        },
        loadprojects(){
            let xhr = new XMLHttpRequest();
            xhr.open("GET","/api/projects");
            xhr.onreadystatechange = ()=>{
                if(xhr.readyState === 4){
                    if(xhr.status === 200) {
                        let projects = JSON.parse(xhr.responseText).reverse();
                        this.projects = projects;
                    }
                }
            }
            xhr.send();
        },
        readTimer(){
            let time = document.querySelectorAll('[class*="Stoppuhr-numbers__"]');
            let duration = ((((parseInt(time.item(0).textContent) * 60) +
                parseInt(time.item(1).textContent)) * 60) +
                parseInt(time.item(2).textContent));

            const sendbutton = document.getElementsByClassName("main__button")[0];
            let comment = document.getElementsByClassName("main__comment")[0].value;
            let selectedIndex = document.getElementsByClassName("main__select").item(0).selectedIndex;
            let projectname  = document.getElementsByClassName("main__select").item(0).options[selectedIndex].text;

            if(duration === 0){
                this.buttonmsg = "Duration is Zero!";
                sendbutton.style.background = "red";
                setTimeout(() => {sendbutton.style.background = "#C0C0C0";this.buttonmsg = "Submit";}, 1500)
                return
            }else if(projectname === "Select Project"){
                this.buttonmsg = "You need to select a project!";
                sendbutton.style.background = "red";
                setTimeout(() => {sendbutton.style.background = "#C0C0C0";this.buttonmsg = "Submit";}, 1500)
                return
            }


            let data = new FormData();
            data.append('duration',duration);
            data.append('comment',comment);
            data.append('projectname',projectname);

            let xhr = new XMLHttpRequest();
            xhr.open('POST','/api/projects/times/add');
            xhr.onreadystatechange = ()=>{
                if(xhr.readyState === 4){
                 if(xhr.status === 201){
                    this.buttonmsg = "Stored Successfully!";
                     sendbutton.style.background = "lightgreen";
                     this.resetwatch = true;
                     document.getElementsByClassName("main__comment")[0].value = null;

                     setTimeout(() => {
                         sendbutton.style.background = "#C0C0C0";
                         this.buttonmsg = "Submit";
                         this.resetwatch = false;
                        }, 1500)
                 }
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
        display: grid;
    }

    .grid-container__menu{
        display: flex;
        flex-direction: row;
        grid-column: 1;
        grid-row: 1;
        justify-content: space-between;
        padding:10px;
    }
    .menu__analytics{
        border-radius: 10px;
        background: #C0C0C0;
        border:#6C6C6C solid;
    }
    .menu__settings {
        border-radius: 10px;
        background: #C0C0C0;
        border:#6C6C6C solid;
    }

    .grid-container__main{
        display:grid;
        grid-column: 1;
        margin-top: 20px;

    }
    .main__add {
        grid-row: 2;
        grid-column: 1;
        margin-right: 10px;
    }
    .main__select{
        grid-row: 2;
        grid-column: 1;
        text-align-last: center;
        margin-left: 70px;
    }
    .main__stopwatch{
        grid-row: 3;
        grid-column: 1;
        height: 400px;
    }
    .main__button{
        grid-row: 5;
        grid-column: 1;
        margin-top: 10px;
        margin-right: 20%;
        margin-left: 20%;
        background: #C0C0C0;
        border-radius: 5px;
        border:#6C6C6C solid;
    }
    .main__comment{
        grid-row: 4;
        grid-column: 1;
        margin-right: 15%;
        margin-left: 15%;
        border-radius: 5px;
    }
    button:active{
        transform: scale(0.9);
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: block;
        z-index: 9999;
    }
    .overlay-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        text-align: center;
        width:50%;
        border-radius: 5px;

    }
    .close-button {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 20px;
    }
</style>
