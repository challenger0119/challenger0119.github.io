<?php
    if (!isset($_COOKIE["username"])){
        setrawcookie("URL",$_SERVER['REQUEST_URI']);
        header("Location: imaginationlogin.php"); 
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Imagination Web</title>
    <style>
        .paddingleft{
            margin-top:20px;
            padding-left:20px;
        }
        .margintop{
            margin-top:5px;
        }
        .toedge{
            margin-top:20px;
            padding-left:20px;
            padding-right:20px;
        }
    </style>
    <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap.min.css">
    <script src="getlocation.js"></script>
    <script src="jsutils.js"></script>
</head>
<body class="toedge">
    <h3>Imagination Web </h3>
    

    <p>iOS --> <a href="http://github.com/challenger0119/Imagination" target="_blank">Imagination</a></p>
    <p id="onlinecountdown"></p>
    <hr />
    <hr />
    <form action="imagination.php" method="post">
        <div class="box-body">
        	<!--
            <label>状态如何？</label>
            <div id="mood" class="radio">  
                <label>
                    <input name="moodOption" type="radio" value="option1" />Good &nbsp;   
                </label>

                <label>
                    <input name="moodOption" type="radio" value="option2" />OK &nbsp;  
                </label>
                <label>
                    <input name="moodOption" type="radio" value="option3" />Not Good &nbsp;  
                </label>
            </div>
        -->
            <div class="form-group">
            	<!--
                <label>有什么想说的吗？</label>
            -->
                <textarea name="content" class="form-control" rows="3" placeholder="记下来吧"></textarea>
            </div>
            <!--
            <div class="form-group">
                <label>你在哪里呢？</label>
                <input id="location" name="location" class="form-control" type="text" />
            </div>
        -->
        </div>
        
        <div class="box-footer">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Done</button>
        </div>
    </form>
    <hr />
    </div>

    <script>
        //getLocation();
        setRTimeToID("onlinecountdown");
        setHitokotoToID("hitokoto");
    </script>
    <script src="../lib/jQuery/jquery-3.2.0.min.js"></script>
    <script src="../lib/bootstrap/js/bootstrap.min.js"></script>
    
</body>

</html>
<footer>
    <div id="hitokoto"></div>
    <hr/>
    <h6>内容参考： <a href="http://www.w3school.com.cn" target="_blank">W3school</a>、<a href="https://github.com/almasaeed2010/AdminLTE" target="_blank">AdminLTE</a></h6>
</footer>
