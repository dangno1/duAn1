<?php
    session_start();
    require('./model/connect.php');
    require('./model/bookedRoom.php');
    if(!empty($_GET['kind_of_room_id'])) {

        $kind_of_room_id = $_GET['kind_of_room_id'];

        $sql = "SELECT * FROM `kindroom` WHERE `kind_of_room_id` = $kind_of_room_id";
        $result = $connect->query($sql);
        $result->execute();
        $kindRoom = $result->fetch();

        // anh room
        $sql_img = "SELECT * FROM `roomimage` WHERE `kind_of_room_id` = $kind_of_room_id";
        $result = $connect->query($sql_img);
        $result->execute();
        $room_img = $result->fetchAll();

        //get comment
        $commentSql = "SELECT comment.content_comment, user.name_user FROM comment INNER JOIN user ON comment.user_id = user.user_id WHERE  comment.status ='Đã Duyệt'";
        $result = $connect->query($commentSql);
        $result->execute();
        $comments = $result->fetchAll();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiet</title>
    <script src="https://kit.fontawesome.com/290fc3f375.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./view/css/deitails.css">
</head>

<body>
    <div class="container">
        <?php require('./view/header.php'); ?>
        <?php 
            if(!empty($kindRoom)) {
        ?>
        <div class="deitail-room">
            <div class="tile">
                <span>Big Hotel</span>
                <h2><?php echo $kindRoom['kind_of_room'] ?></h2>
            </div>
            <div class="banner">
                <div class="banner-left">
                    <img src="./controller/kindRoom/<?php echo $kindRoom['image'] ?>" width="1024px" height="683px">
                </div>
                <div class="banner-right">
                    <?php 
                        foreach ($room_img as $item) {
                    ?>
                    <img src="./controller/kindRoom/<?php echo $item['image_room'] ?>" width="100%" height="335px">
                    <?php 
                        }
                    ?>
                </div>
            </div>
        </div>
        <main>
            <div class="produc">
                <div class="room-desc">
                    <h3>Mô tả phòng</h3>
                    <p><?php echo $kindRoom['describe'] ?></p>
                    <div class="room-uitil">
                        <div><i class="fa-solid fa-wifi"></i>
                            <p>Wife</p></i>
                        </div>
                        <div><i class="fa-solid fa-tv"></i>
                            <p>TV</p>
                        </div>
                        <div>
                            <i class="fa-solid fa-bath"></i>
                            <p>Nhà Tắm</p>
                        </div>
                        <div>
                            <i class="fa-solid fa-bottle-water"></i>
                            <p>Vật dụng tắm rửa</p>
                        </div>
                        <div>
                            <i class="fa-solid fa-suitcase-medical"></i>
                            <p>Tủ y tế</p>
                        </div>
                        <div>
                            <i class="fa-sharp fa-solid fa-snowflake"></i>
                            <p>Diều hòa</p>
                        </div>
                        <div>
                            <i class="fa-sharp fa-solid fa-bed"></i>
                            <p>Giường ngủ</p>
                        </div>
                    </div>
                    <div class="comment-list">
                        <h3>Comment về sản phẩm</h3>
                        <?php 
                    if(!empty($comments)) :?>
                        <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <h5 class="username">
                                <?php echo "-". $comment['name_user'];?>
                            </h5>
                            <div class="comment-content">
                                <?php echo $comment['content_comment'];?>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <?php endif;?>
                        <hr>
                    </div>
                </div>
                <div class="room-price">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="price">
                            <h4>Bắt Đầu Đặt</h4>
                            <input readonly="False" name="price_room" id="price_room" value="<?=$kindRoom['price']?>"
                                class="price-item">
                            <div class="night">VND OVERNIGHT</div>
                        </div>
                        <div class="date-room">
                            <div class="pick-date">
                                <h4>Chọn ngày Vào</h4>
                                <input name="start_time" type="date" id="dt" class="calendar" required>
                                <h4>Chọn ngày trả phòng</h4>
                                <input name="end_time" type="date" id="de" class="calendar" onchange="dateEnd()"
                                    required>
                            </div>
                            <h4>Số Người</h4>
                            <div class="buttons_added">
                                <div class="room">
                                    <button id="reduce">-</button>
                                    <input name="amount" type="number" min="1" value="1" id="number_room">
                                    <button id="add">+</button>
                                </div>
                            </div>
                        </div>
                        <?php
                        date_default_timezone_set("Asia/Ho_Chi_Minh");
                        if (isset($_POST['start_time']) && isset($_POST['end_time']) && isset($_POST['price_room'])) {

                            if (isset($_SESSION['user_id'])) {
                                $user_id = $_SESSION['user_id'];
                                $bookedRoom = new BookedRoom(); 
                                $start_time = $_POST['start_time'];
                                $end_time = $_POST['end_time'];
                                $amount = $_POST['amount'];                       
                                $price_room = $_POST['price_room'];
                                $bookedRoom->add($kind_of_room_id, $user_id, $start_time, $end_time, $amount, $price_room,);
                            } else{
                    ?>
                        <script>
                        if (confirm('Đăng nhập để tiếp tục')) {
                            window.location = "view/dangnhap.php";
                        }
                        </script>
                        <?php
                            }                            
                        }
                    ?>
                        <div class="book">

                            <button type="submit" class="book-room">Đặt Phòng Ngay</button>
                        </div>
                    </form>
                </div>
            </div>
            <h3> Khám phá Khách sạn</h3>
            <div class="room-next">
                <div class="zoom next">
                    <img src="./view/img/deitail/next.jpg" alt="">
                    <p>Phòng Tổng Thống</p>
                </div>
                <div class="zoom next">
                    <img src="./view/img/deitail/next2.jpg" alt="">
                    <p>Phòng Nhóm 2</p>
                </div>
                <div class="zoom next">
                    <img src="./view/img/deitail/next3.png" alt="">
                    <p>Phòng Thủ Tướng</p>
                </div>
            </div>
            <?php
            require('./view/comment.php');
        ?>
        </main>
        <div id="wrapper">
            <input type="checkbox" name="" class="switch-toggle" id="light-dark">
        </div>
        <?php 
            } else {
        ?>
        <h2>Phong ko ton tai</h2>
        <?php
            }
        ?>
        <?php require('./view/footer.php'); ?>
        <script>
        let price = document.getElementById('price_room');

        document.getElementById('add').onclick = function(event) {
            event.preventDefault()
            let number_room = document.getElementById('number_room')
            number_room.value++;
        }
        document.getElementById('reduce').onclick = function(event) {
            event.preventDefault()
            let number_room = document.getElementById('number_room')
            if (number_room.value > 1) {
                number_room.value--;
            }
        }
        var checkbox_toggle = document.getElementById('light-dark');
        checkbox_toggle.addEventListener('change', function() {
            // THêm class dark cho body
            document.body.classList.toggle('dark');
        });

        function dateEnd() {
            let dateStar = document.getElementById('dt').value
            let dateEnds = document.getElementById('de').value

            var days = daysdifference(dateStar, dateEnds);

            function daysdifference(firstDate, secondDate) {
                var startDay = new Date(firstDate);
                var endDay = new Date(secondDate);

                var millisBetween = startDay.getTime() - endDay.getTime();
                var days = millisBetween / (1000 * 3600 * 24);

                return Math.round(Math.abs(days));
            }
            document.getElementById('price_room').value = <?=$kindRoom['price']?> * days;
        }
        </script>
    </div>
</body>

</html>