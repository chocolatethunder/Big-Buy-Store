Please follow this very carefully. This is a slightly sensitive installation:

1.) Download the latest version of the WAMP server and install it. Make sure you 
	are getting a Green Icon in your system tray. (Appserv is garbage. Do not use it.)
2.) Sync to the new version of the repository from Github.

-- Do not proceed until previous steps are complete. --

3.) Open the repo directory and navigate inside the www folder. Copy the link to this folder. C:\\ the whole thing.
4.) Left click the green WAMP icon in the system tray
		WAMP Icon > Apache > Alias directories > Add an alias
	4a.) give it a simple name
	4b.) paste the link to the directory (don't worry if you mess up it can be altered later)

-- Do not proceed until previous steps are complete. --

Now, let's configure phpmyadmin so that you can admin the database. 

5.) Download the configure.zip from our Slack. There should be 2 files in it. 
	5a.) Place the config.inc.php in C:\wamp64\apps\phpmyadmin4.6.4\ directory and replace the current file. 
	5b.) Place the pconfig.inc.php in www\inc folder. Rename it to config.inc.php.
	
	** SECURITY NOTE **: These files must not be published anywhere. They contain password to our Amazon RDS Database instance. 
						The file mentioned in 5b has been included in .gitignore file so that it doesn't accidently get published. 

-- Do not proceed until previous steps are complete. --
					
6.) Open your newly replaced C:\wamp64\apps\phpmyadmin4.6.4\config.inc.php file and note the user and password
7.) WAMP Icon > phpmyadmin > Select Remote Server from the current server 
	drop down (top left corner of the GUI). Enter the credentials from 6. and you are in. We are working with MasterDB.


*** TEST THE INSTALLATION ***

You may now open a browser of your choice and go to http://localhost/{name_you_gave_in_4a}



**** IMPORTANT NOTES ****

- The RDS is going to be shared between the 3 of us. Because there is no way to "git" it.
- All the project files are organized under www directory of the git repo.


If you have any issues please ask!


