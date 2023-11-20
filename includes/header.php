<link rel="stylesheet" type="text/css" href="../css/header.css">
<header>
		<nav>
			<ul>
				<li><a href="../room/quest_list.php">Home</a></li>
				<li><a href="../comment/comment_page.php">Comments</a></li>
                <?php
                session_start();


				if(isset($_SESSION['user_id'])){
					echo "<li><a href='../profile/profile.php'>Profile</a></li>";
				}
				
				if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])){
					echo "<li><a href='../admin/admin.php'>Admin info</a></li>";
                    echo "<li><a href='../room/quest_form.php'>Add quest room</a></li>";
					echo "<li><a href='../profile/profile_info.php'>User's info</a></li>";
					echo "<li><a href='../charts/chart_graphs.php'>Charts</a></li>";

					
	
				}

                
				
				

				if(isset($_SESSION['admin_id']) || isset($_SESSION['user_id'])){
				
                    echo "<form action='../register_login/logout.php' method='post'>";
    				echo "<button type='submit' name='logout'>Logout</button>";
				    echo "</form>";
                }

				else{
                    echo "<li><a href='../register_login/registerform.php'>Register</a></li>";
				    echo "<li><a href='../register_login/loginform.php'>Login</a></li>";
                }
				?>
				
				
			</ul>
		</nav>
	</header>