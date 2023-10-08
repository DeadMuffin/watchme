<template>
    <div v-bind="$attrs">
    <div class="select-wrapper">
        <select v-model="selectedOption" @change="showOverlayfunc">
            <option disabled selected>ADD</option>
            <option>Project</option>
            <option>Time</option>
        </select>
    </div>
    </div>
    <div class="overlay" v-show="showOverlay">
        <!-- Overlay-Inhalt -->
        <div class="overlay-content">
            <span class="close-button" @click="hideOverlay">X</span>
            <h2>Add New {{ selectedOption }}</h2>


            <div v-if="selectedOption === 'Project' && newrequestsucceded === ''">
               <input class="overlay-content__project" placeholder="Project Name"/>
                <button @click="addnewproject">Submit</button>
            </div>

            <div v-if="selectedOption === 'Time' && newrequestsucceded === ''" class="time-entry-form">
                <div class="form-group">
                    <label for="projectSelect">Project Name:</label>
                    <select id="projectSelect" class="overlay-content__time__name">
                        <option v-for="project in projects">{{ project.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="theselectedDate">Date:</label>
                    <input type="date" id="theselectedDate" v-model="selectedDate" class="overlay-content__time__date">
                </div>
                <div class="form-group">
                    <label for="durationInput">Duration:</label>
                    <input type="text" id="durationInput" class="overlay-content__time__duration" placeholder="min">
                </div>
                <div class="form-group">
                    <label for="commentTextarea">Comment:</label>
                    <textarea id="commentTextarea" class="overlay-content__time__comment" placeholder="Enter Comment here!"></textarea>
                </div>
                <div class="form-group">
                    <button @click="addnewtime" class="submit-button">Submit</button>
                </div>
            </div>

            <h1 class="colored__gold">{{newrequestsucceded}}</h1>
        </div>
    </div>
</template>

<style scoped>
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
.colored__gold{
    color: gold;
}
.select-wrapper{
    display: inline;
}
</style>

<script>
export default {
    name: "NewButton",
    emits: ['newproject'],
    props:['projects'],
    data() {
        const todaysDay = new Date().toISOString().slice(0, 10); // Get today's date in "yyyy-MM-dd" format
        return {
            today: todaysDay,
            selectedOption: "ADD",
            showOverlay: false,
            newrequestsucceded: "",
            selectedDate: this.today,
        };
    },
    methods: {
        showOverlayfunc() {
            this.showOverlay = true;
            this.newrequestsucceded= "";
            this.selectedDate= this.today;
        },
        hideOverlay() {
            this.showOverlay = false;
            this.selectedOption = "ADD";
            this.newrequestsucceded= "";
            },
        addnewproject(){
            let name = document.getElementsByClassName("overlay-content__project").item(0).value ?? "";

            if(name === "")return;

            let data = new FormData();
            data.append('name', name);
            let xhr = new XMLHttpRequest();
            xhr.open("POST","api/projects/add")
            xhr.onreadystatechange =  () => {
                if(xhr.readyState === 4){
                    if(xhr.status === 201) {
                        this.newrequestsucceded = name + " successfully created!";
                        setTimeout(() => this.hideOverlay(), 1500)
                        this.$emit('newproject', name);
                    }
                }
            }
            xhr.send(data);

        },
        addnewtime(){
            let inputdata = document.querySelectorAll('[class*="overlay-content__time__"]');
            let name = inputdata.item(0).value ?? "";
            let duration = inputdata.item(2).value ?? -1;
            let comment = inputdata.item(3).value ?? "";
            let date = this.selectedDate;
            if (name === "" || duration < 0) return;

            duration = duration*60;

            let data = new FormData();
            data.append('projectname', name);
            data.append('duration', duration);
            data.append('comment', comment);
            data.append('created_at', date);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "api/projects/times/add");
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    if (xhr.status === 201) {
                        this.newrequestsucceded = "Time successfully created!";
                        setTimeout(() => this.hideOverlay(), 1500);
                    }
                }
            };
            xhr.send(data);

        }
    }
};
</script>
