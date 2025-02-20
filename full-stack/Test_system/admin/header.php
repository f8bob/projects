<div style="text-align: center" class="navig">
    <a href="index.php" <?php if (strpos($_SERVER['REQUEST_URI'],'index.php') !== false) echo 'class="active"' ?>>Create/Edit test</a>
    <a href="tests.php" <?php if (strpos($_SERVER['REQUEST_URI'],'tests.php') !== false) echo 'class="active"' ?>>All tests</a>
    <a href="results.php" <?php if (strpos($_SERVER['REQUEST_URI'],'results.php') !== false) echo 'class="active"' ?>>Results</a>
</div>

<style>
    .navig {
        margin-top: 50px;
    }

    .navig a {
        text-decoration: none;
        color: black;
        font-style: italic;
        margin: 0 20px;
        font-weight: 500;
    }

    .navig a.active {
        font-weight: 900; /* Increase font weight for active page */
    }
</style>