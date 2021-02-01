</head><!--/head-->

<body class="homepage">

    <header id="header">
        

        <nav class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/admin"><img src="/system/images/logo.png" alt="logo"></a>
                </div>
				
                <div class="collapse navbar-collapse navbar-right">
                    <ul class="nav navbar-nav">
                        <li <?=$page=='home'?'class="active"':''?>><a href="/">Home</a></li>
                        <li <?=$page=='contact'?'class="active"':''?>><a href="/contact">Contact</a></li>
                        <!-- <li <?=$page=='rss'?'class="active"':''?>><a href="/rss" target="_blank">RSS Feed</a></li> -->
                        <li class="hidden-md hidden-lg"><a href="javascript:toggleSidebar()">Sidebar</a></li>
                    </ul>
                </div>
            </div><!--/.container-->
        </nav><!--/nav-->
		
    </header><!--/header-->               
    
    <? if (isset($article_title)) { print $article_title; } ?>
    <? if (isset($article)) { print $article; } ?>
    <div class="page-cover"></div>
    <div id="content" class="container">

	