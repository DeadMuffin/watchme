<template>

    <div class="char-minutes-per-timeperiod">
        <div class="date-time-picker__container">
            <vue-ctk-date-time-picker
                v-model="raw_date"
                :label="'Select Date or Interval'"
                :range="true"
                :no-shortcuts="true"
                :noClearButton="true"
            ></vue-ctk-date-time-picker>
            <button class="date-time-picker__button" @click="resetDate">X</button>
        </div>
        <select class="projects__select" v-model="selectedproject">
            <option>All Projects</option>
            <option v-for="project in projects">
                {{project.name}}
            </option>
        </select>
        <canvas id="char_minutes_per_timeperiod"></canvas>
    </div>

    <div class="container--table--char">
    <div class="char-minutes-per-project">
        <canvas id="char_minutes_per_projects"></canvas>
    </div>
    <div class="table-all-displayed-projects">
        <table class="custom-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Duration</th>
                <th>Comment</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(projectid, index) in filteredData_minutes_per_timeperiod['project_id']" :key="index" v-show="projectid !== null">
                <td>{{ projectid }}</td>
                <td>{{ filteredData_minutes_per_timeperiod['data'][index] }}</td>
                <td>{{ filteredData_minutes_per_timeperiod['comment'][index] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    </div>
</template>

<script>
import { Chart } from "chart.js/auto";
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
import zoomPlugin from 'chartjs-plugin-zoom/dist/chartjs-plugin-zoom.min.js';
export default {
    components:{
        VueCtkDateTimePicker},
    props:['update','projects'],
    data() {
        return {
            selectedproject: 'All Projects',
            data:   [],
            raw_date: '',
        };
    },
    mounted() {
        Chart.register(zoomPlugin);
        this.createChart_minutes_per_project();
        this.createChart_minutes_per_timeperiod();
        this.getData();
    },
    watch: {
        update: {
            handler(value){
                if(value)
                    this.getData();
            }
        },
        filteredData_minutes_per_project: {
            handler() {
                this.updateChart_minutes_per_project();
            },
            deep: true,
        },
        filteredData_minutes_per_timeperiod: {
            handler() {
                this.updateChart_minutes_per_timeperiod();
            },
            deep: true,
        },
    },
    methods: {
        resetDate(){
            this.raw_date = '';
            this.selectedproject = "All Projects";
            this.updateChart_minutes_per_timeperiod();
        },
        createChart_minutes_per_timeperiod() {
            const ctx = document.getElementById("char_minutes_per_timeperiod");
            this.char_minutes_per_timeperiod = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: this.filteredData_minutes_per_timeperiod.labels,
                    datasets: [{
                        label: 'Hours per timeperiod',
                        data: this.filteredData_minutes_per_timeperiod.data,
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    plugins: {
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true,
                                    modifierKey: 'shift',
                                },
                                pinch: {
                                    enabled: true,
                                    modifierKey: 'shift',
                                },
                                drag:{
                                    enabled:true,
                                    modifierKey: 'shift',
                                },
                                mode: 'x',
                            }
                        }
                    }
                }
            });
        },
        updateChart_minutes_per_timeperiod() {
            if (this.char_minutes_per_timeperiod) {
                this.char_minutes_per_timeperiod.data.labels = this.filteredData_minutes_per_timeperiod.labels;
                this.char_minutes_per_timeperiod.data.datasets[0].data = this.filteredData_minutes_per_timeperiod.data;
                this.char_minutes_per_timeperiod.data.datasets[0].backgroundColor = this.generateRandomColors(this.filteredData_minutes_per_timeperiod.labels.length);
                this.char_minutes_per_timeperiod.update();
            }
            this.char_minutes_per_timeperiod.resetZoom();
        },

        createChart_minutes_per_project() {
            const ctx = document.getElementById("char_minutes_per_projects");
            this.char_minutes_per_projects = new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: this.filteredData_minutes_per_project.labels,
                    datasets: [
                        {
                            label: "Min",
                            data: this.filteredData_minutes_per_project.data,
                            borderWidth: 1,
                            backgroundColor: [],
                        },
                    ],
                },
                options: {},
            });
        },
        updateChart_minutes_per_project() {
            if (this.char_minutes_per_projects) {
                this.char_minutes_per_projects.data.labels = this.filteredData_minutes_per_project.labels;
                this.char_minutes_per_projects.data.datasets[0].data = this.filteredData_minutes_per_project.data;
                this.char_minutes_per_projects.data.datasets[0].backgroundColor = this.generateRandomColors(this.filteredData_minutes_per_project.labels.length);
                this.char_minutes_per_projects.update();
            }
        },
        generateRandomColors(count) {
            const colors = [];

            for (let i = 0; i < count; i++) {
                const color = `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(
                    Math.random() * 256
                )}, ${Math.floor(Math.random() * 256)}, 0.8)`;
                colors.push(color);
            }

            return colors;
        },
        getData() {
            // Ajax-Anfrage, um die Daten zu erhalten und this.data aktualisieren
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "/api/all");
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        this.data = JSON.parse(xhr.responseText);
                    }
                }
            };
            xhr.send();
        },
        filterData_minutes_per_project() {
            const raw_data = this.data;

            let result = [[], []];
            // Extrahiere eindeutige Projektnamen
            const projectNames = [...new Set(raw_data.map(entry => entry.project_id))];
            result[0] = projectNames;

            // Extrahiere Durations pro Projekt
            for (let i = 0; i < projectNames.length; i++) {
                const projectName = projectNames[i];
                const durations = raw_data
                    .filter(entry => entry.project_id === projectName)
                    .reduce((total, entry) => total + entry.duration, 0);
                result[1][i] = durations / 60;
            }
            return result;
        },
        filterData_minutes_per_timeperiod() {
            let raw_data = this.data ;

            if(raw_data[0] === undefined) return [[0][0]];


            return this.generateDateArray(raw_data);
        },
        generateDateArray(dates) {
            let startDate;
            let endDate;

            if (this.date !== "") {
                startDate = new Date(this.date.start);
                endDate = new Date(this.date.end);
            } else {
                startDate = new Date(dates[0].created_at);
                endDate = new Date(dates[dates.length - 1].created_at);
            }

            const resultArray = [[], [], [], []];
            const dateMap = new Map();

            let currentDate = new Date(startDate); // Kopie des Startdatums, um es zu verändern

            while (currentDate <= endDate) {
                const dateString = currentDate.toISOString().split('T')[0]; // Extrahiere das Datum als String im Format "YYYY-MM-DD"

                resultArray[0].push(dateString); // Füge das Datum zum ersten Array hinzu

                // Überprüfe, ob es einen Eintrag für das Datum in den ursprünglichen Daten gibt
                const entry = dates.find(entry => {
                    const entryDate = new Date(entry.created_at);
                    return entryDate.toISOString().split('T')[0] === dateString &&
                        (this.selectedproject === 'All Projects' || entry.project_id === this.selectedproject);
                });

                const duration = entry ? entry.duration : 0;
                resultArray[1].push(duration / 60 / 60); // Füge die Duration zum zweiten Array hinzu

                if (entry) {
                    resultArray[2].push(entry.project_id); // Füge die project_id zum dritten Array hinzu
                    resultArray[3].push(entry.comment); // Füge den comment zum vierten Array hinzu
                } else {
                    resultArray[2].push(null); // Füge null hinzu, wenn kein passender Eintrag gefunden wurde
                    resultArray[3].push(null); // Füge null hinzu, wenn kein passender Eintrag gefunden wurde
                }

                currentDate.setDate(currentDate.getDate() + 1); // Inkrementiere den aktuellen Tag um 1
            }

            return resultArray;
        }

    },

    computed: {
        date(){
            if(this.raw_date === '') return "";

            let start = this.raw_date.start.substring(0,10);
            let tmp = this.raw_date.end;
            if(tmp === null) {
                return {
                    start: start,
                    end: ""
                }
            }
            let end = tmp.substring(0,10);
            return {
                start:  start,
                end:    end
            }
        },
        filteredData_minutes_per_project() {
            let filtereddata = this.filterData_minutes_per_project();

          return  {
                labels: filtereddata[0],
                    data: filtereddata[1],
            };

        },
        filteredData_minutes_per_timeperiod() {
            let filtereddata = this.filterData_minutes_per_timeperiod();

            return  {
                labels: filtereddata[0],
                data: filtereddata[1],
                project_id: filtereddata[2],
                comment: filtereddata[3]
            };

        },
    },
};
</script>

<style scoped>
.container--table--char{
    display: flex;
}

.char-minutes-per-project{
    height: auto;
    width: 35%;
}

.date-time-picker__container {
    position: relative;
    display: inline-flex;
    width: 400px;
}

.date-time-picker__button {
    position: absolute;
    top: 50%;
    right: 5%;
    transform: translate(50%, -50%);
    z-index: 1;
    border-radius: 10px;
}
.projects__select{
    margin-left: 10px;
}
.table-all-displayed-projects{
    width: 100%;
}
.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.custom-table th,
.custom-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ccc;
}

.custom-table th {
    background-color: #f0f0f0;
}

.custom-table tbody tr:hover {
    background-color: #f9f9f9;
}

.custom-table tbody tr:nth-child(even) {
    background-color: #f5f5f5;
}
</style>
