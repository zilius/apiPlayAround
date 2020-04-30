<h2>Requimets</h2>
<ul>
<li>Docker</li>
<li>Makefile support</li>
</ul>

<h2>Launching</h2>
To start the app, first go to Makefile and change `HTTP_PORT` to use free port on your machine. It uses `8080` port by default.
After making sure, that the port is free, open terminal and CD into project root directory and launch `make init` command (docker is not launched in a background).

If everything went well, all composer dependencies should be downloaded and project should be running, open the browser ant enter `http://localhost:8080/` (or any other port if you changed it) press enter and you should see solutions of the task.