<link rel="stylesheet" type="text/css" href="header.css">
<header>
		<nav>
			<ul>
				<li><a href="home.php">Home</a></li>
				<li><a href="comment_page.php">Comments</a></li>
                <?php
                session_start();


				if(isset($_SESSION['user_id'])){
					echo "<li><a href='profile.php'>Profile</a></li>";
				}
				
				if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])){
					echo "<li><a href='admin.php'>Admin info</a></li>";
                    echo "<li><a href='quest_form.php'>Add quest room</a></li>";
					echo "<li><a href='profile_info.php'>User's info</a></li>";

					
	
				}

                
				
				

				if(isset($_SESSION['admin_id']) || isset($_SESSION['user_id'])){
				
                    echo "<form action='logout.php' method='post'>";
    				echo "<button type='submit' name='logout'>Logout</button>";
				    echo "</form>";
                }

				else{
                    echo "<li><a href='registerform.php'>Register</a></li>";
				    echo "<li><a href='loginform.php'>Login</a></li>";
                }
				?>
				
				
			</ul>
		</nav>
	</header>