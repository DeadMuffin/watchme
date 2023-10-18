<h1>Watchme</h1>
Watchme is a simple time tracking software with analytics. Everything is hosted on a local server in Docker, and the data is stored locally in MariaDB.

<h2>Introduction</h2>
This project is far from perfection, but it's a fun project for personal use, designed to help me learn new things and track my various projects. Despite the abundance of software options available, I tried to utilize frameworks and libraries I already knew and some that I wanted to learn.

The main goal is to store data about:

When I worked on which project?
How long did I work on each project?
What did I do during that time?


<h2>Features</h2>
<b>Project Management:</b> Easily create and manage your projects. <br>

<b>Time Tracking:</b> Add detailed time entries with comments for each project. <br>

<b>Stopwatch:</b> Use the built-in stopwatch to track your work in real-time. You can add the recorded time directly to a project. <br>

<b>Reminders:</b> Set up two timers to remind you to take breaks – one for drinking and one for standing. Customize the timer durations in the settings. <br>

<b>Analytics:</b> Get insights into your work: <br>

<b>Pie Chart:</b> Visualize the distribution of time spent on each project. <br>

<b>Bar Chart:</b> Track daily work durations. Apply filters for specific projects and time periods. <br>

<b>Detailed Table:</b> View additional information about your work, including the date, duration, and comments. <br>

<b>Easy Deployment:</b> Deploy Watchme with a single command for hassle-free setup. <br>

<b>Bulk Import/Export:</b> Import new projects or time entries via API, and export your data conveniently. <br>

<h2>Installation and Getting Started</h2>
Requirements:

- Ensure you have Docker installed and running on your system.

- Download Configuration Files: Obtain the Dockerfile, Docker-compose.yml and init.sql from this repository. Store them in the location where you want to store your project data.

- Navigate to the Project Directory: Open your terminal and navigate to the directory where you've placed the configuration files and where you want the data to be stored.

- Start the Application: In the terminal, execute the command docker-compose up. This may take some time, but upon completion, a "data" folder will be created to store your database data.

- Access the Application: Open your web browser and visit "localhost:8000" to access the Watchme application. From there, you can start tracking your projects.


<h2>Prerequisites</h2>
You only need Docker to run this project. The project uses several libraries and frameworks like Vue, Laravel, composer, npm, Vite, Axios, Chart.js, chartjs-plugin-zoom, vue-ctk-date-time-picker 
and vue-timer-hook, but Docker manages these dependencies, so you don't need to install them separately.

<h2>Usage</h2>

![image](https://github.com/DeadMuffin/watchme/assets/114155136/c10b4175-9773-4b07-bf85-860a52f0fa2f)

- Create a new project by clicking "Add," and a popup will appear.

![image](https://github.com/DeadMuffin/watchme/assets/114155136/aab36067-2d10-4b39-8362-4302769b24bd)

- After creating some projects, choose one from the dropdown and start the stopwatch to track time.
You can add comments about what you're doing while working. If you need a break, you can pause and continue later or reset the watch.

![image](https://github.com/DeadMuffin/watchme/assets/114155136/537d8c4f-a14e-4c0b-94f7-b16c2eaac130)

- Start and stop reminders for drinking and standing. Green indicators show active reminders, and you can hover over them to see the time remaining.

![image](https://github.com/DeadMuffin/watchme/assets/114155136/5139eca3-e5a0-49e5-af04-9ab82b656477)

- Customize timer durations using the settings in the upper left corner.

![image](https://github.com/DeadMuffin/watchme/assets/114155136/67ee91a1-e454-4c60-95e0-a6508441142c)

- Manually add times to specific projects. Choose a project, date, duration, and write a comment about your work.

![image](https://github.com/DeadMuffin/watchme/assets/114155136/56bb7680-0eab-4924-8999-98696c4460b6)

- Switch to the analytics tab to see your projects, time spent, and work details. You can filter by date, date interval, or specific projects.
Remove filters by reloading the page or clicking the "x" icon.

![image](https://github.com/DeadMuffin/watchme/assets/114155136/0eb94fef-ee4c-4616-b64d-895ae894c998)
-Zoom in on charts by using Shift + mouse scroll or pinching on tablets. You can also hold Shift and mark a specific region to zoom in.


- To use the import or export features, you need to download curl or similar software.
  
<h2> Import Projects </h2>

![image](https://github.com/DeadMuffin/watchme/assets/114155136/954bf690-e01b-47d2-b27a-a90c4858aa43)

curl -X POST -H "Content-Type: application/json" -d @projects.json http://localhost:8000/api/import/projects

- Replace @projects.json with your file containing the project data you want to store.

<h2>Import Times</h2>

![image](https://github.com/DeadMuffin/watchme/assets/114155136/060cc918-3e62-457a-ad0a-0e7f94b6515e)

curl -X POST -H "Content-Type: application/json" -d @times.json http://localhost:8000/api/import/times

- For times, specify the duration in hours in your JSON file.

Feel free to reach out for any questions or support.
