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

            <div v-if="selectedOption === 'Time' && newrequestsucceded === ''">
                <select class="overlay-content__time__name" placeholder="Project Name">
                    <option v-for="project in projects">
                    {{project.name}}</option>
                </select>
                <input class="overlay-content__time__duration" placeholder="Duration"/>min
                <br>
                <textarea class="overlay-content__time__comment" placeholder="Enter Comment here!"/>
                <br>
                <button @click="addnewtime">Submit</button>
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
        return {
            selectedOption: "ADD",
            showOverlay: false,
            newrequestsucceded: ""
        };
    },
    methods: {
        showOverlayfunc() {
            this.showOverlay = true;
            this.newrequestsucceded= "";
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
            let duration = inputdata.item(1).value ?? -1;
            let comment = inputdata.item(2).value ?? "";
            if (name === "" || duration < 0) return;
            duration = duration*60;

            let data = new FormData();
            data.append('projectname', name);
            data.append('duration', duration);
            data.append('comment', comment);
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
