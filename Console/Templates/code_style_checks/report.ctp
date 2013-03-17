<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SweatLedger Coding Style Check report</title>

    <link rel="stylesheet" type="text/css" href="/css/framework.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/css/wthstyle.css" />
    <link rel="stylesheet" type="text/css" href="/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="/css/960.css" />
</head>
<body>
    <div id="container">
        <div id="header">
            <h1>SweatLedger Coding Style Check report</h1>
            <h2>Date: <?=$reportDateTime?></h2>
        </div>
        <div id="content">
            <h3>Models PHP errors</h3>
            <pre>
                <?=$phpModelsOutput?>
            </pre>

            <h3>Views PHP errors</h3>
            <pre>
                <?=$phpViewsOutput?>
            </pre>

            <h3>Controllers PHP errors</h3>
            <pre>
                <?=$phpControllersOutput?>
            </pre>

            <h3>JavaScript errors</h3>
            <pre>
                <?=$javascriptOutput?>
            </pre>

            <h3>CSS errors</h3>
            <pre>
                <?=$cssOutput?>
            </pre>
        </div>
    </div>
</body>
</html>
