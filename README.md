Task

Register a short-lived token on the fictional Supermetrics Social Network REST API
 
Fetch the posts of a fictional user on a fictional social platform and process their posts. You will have 1000 posts over a six month period. Show stats on the following: - Average character length of a post / month - Longest post by character length / month - Total posts split by week - Average number of posts per user / month
 
Design the above to be generic, extendable and easy to maintain by other staff members.
 
<h2>Requiments</h2>
<ul>
<li>Docker</li>
<li>Makefile support</li>
</ul>

<h2>Launching</h2>
To start the app, first go to Makefile and change `HTTP_PORT` to use free port on your machine. It uses `8080` port by default.
After making sure, that the port is free, open terminal and CD into project root directory and launch `make init` command (docker is not launched in a background).

If everything went well, all composer dependencies should be downloaded and project should be running, open the browser ant enter `http://localhost:8080/` (or any other port if you changed it) press enter and you should see solutions of the task.

<h2>Output Explanation</h2>
<img src="https://github.com/zilius/apiPlayAround/blob/master/95500631_2640468079567487_1294468847700541440_n.png?raw=true"></img>
I used laravel framework's helper funcion `dd` for rendering output, since there was no requiment for fancy html.
The output is intearctive so you can click on the elements to collapse/ellapse them :)


The initial arrays shows the stats grouped by months, it acts as and index, for example `2020-04` -  will hold statistics posts that was created in april 2020. Values inside the array:

 `Average character length of a post / month` - AvgPostLength, rounded to 2 decimal spaces <br>
 `Longest post by character length / month` - LongestPosts - array of longest posts (though there would be high chance that few post might have same length), each element of array is a sub array of the post info which contains Id receivev from api and original message text. <br>
 `Total posts split by week` - WeeklyPosts associative array, which hold data of how many posts was posted during certain week, `WeekNumberOfYear` => `PostsReceived` <br>
 `Average number of posts per user / month` - AvgPostsPerUser float rounded to two decimal spaces
