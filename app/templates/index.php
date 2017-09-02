<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Slim3 + Tuum</title>
    <link href=’http://fonts.googleapis.com/css?family=Roboto’ rel=’stylesheet’ type=’text/css’>
    <style type="text/css">
        body {
            margin: 0;
            padding: .1em;
            font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: 300;
        }
        div.top {
            border: 1px solid #cccccc;
            background-color: #f0f0f0;
            margin: 1em 0;
            padding: 1em;
        }
        div.top h1 {
            font-size: 40px;
            font-weight: 300;
        }
        div.contents {
            padding: 1em;
        }
    </style>
</head>
<body>

<div class="top">
    <h1>Slim3+Tuum</h1>
    <p><a href="https://github.com/TuumPHP/Respond">Tuum/Responder</a> turns micro framework, such as <a href="https://www.slimframework.com/">Slim 3</a>, into a framework for ordinary HTML based web site :P </p>
</div>

<div class="contents">
    <p>Tuum/Respond provides features to help build an ordinary HTML web site, such as: </p>
    <ul>
        <li>easily access CSRF token in view template,</li>
        <li>automatically sets flash message in view,</li>
        <li>shows previous input values in a html form,</li>
    </ul>
    <p>and maybe more. </p>
    
    <p>To see these features in demo site, execute the command below at public/ folder. </p>
    <pre>php -S localhost:8000 demo.php</pre>
</div>

<div class="footer">
    <p>&nbsp;</p>
    <hr noshade size="1" color="#cccccc">
    <p><em>Tuum</em> means '<em>yours</em>' in Latin.</p>
</div>
</body>
</html>