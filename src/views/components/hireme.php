<div class="hireFormContainner" id="HireForm">
    <form class="hireForm" action="/posthire" method="post">

        <div class="hireTitle">
            <div class="title">Hire <?php echo $worker['username'] ?></div>
            <div class="hireClose" onclick="toggleHire()">❌</div>
        </div>
        <hr>
        <br>
        <div class="hireContent">
            <input type="text" name="worker_id" value="<?php echo $workerid ?>" hidden>
            <div class="detail">
                <div class="title">From:</div>
                <input type="date" name="from" class="input">
            </div>

            <div class="detail">
                <div class="title">To:</div>
                <input type="date" name="to" class="input">
            </div>
            <div class="detail">
                <div class="title">Description:</div>
                <textarea name="description" id="" class="textarea"></textarea>
            </div>
            <button class="btn btn-primary" type="submit">Hire!</button>
        </div>
    </form>
</div>