<template>
    <div class="char-minutes-per-project">
        <canvas id="myChart"></canvas>
    </div>
</template>

<script>
import { Chart } from "chart.js/auto";

export default {
    props:['update'],
    data() {
        return {
            data: [],

        };
    },
    mounted() {
        this.createChart();
    },
    watch: {
        update: {
            handler(value){
                if(value)
                    this.getData();
            }
        },
        filteredData: {
            handler() {
                this.updateChart();
            },
            deep: true,
        },
    },
    methods: {
        createChart() {
            const ctx = document.getElementById("myChart");
            this.chart = new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: this.filteredData.labels,
                    datasets: [
                        {
                            label: "Min",
                            data: this.filteredData.data,
                            borderWidth: 1,
                            backgroundColor: [],
                        },
                    ],
                },
                options: {},
            });
        },
        updateChart() {
            if (this.chart) {
                this.chart.data.labels = this.filteredData.labels;
                this.chart.data.datasets[0].data = this.filteredData.data;
                this.chart.data.datasets[0].backgroundColor = this.generateRandomColors(this.filteredData.labels.length);
                this.chart.update();
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
        filterData() {
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
                result[1][i] = durations/60;
            }
            return result;
        },
    },
    created() {
        this.getData();
    },
    computed: {
        filteredData() {
            let filtereddata = this.filterData();

          return  {
                labels: filtereddata[0],
                    data: filtereddata[1],
            };

        },
    },
};
</script>

<style scoped>
.char-minutes-per-project{
    height: 300px;
    width: auto;
}
</style>
