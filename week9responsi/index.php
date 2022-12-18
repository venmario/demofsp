<?php 
$connection = new mysqli('localhost','root','','fsp-b');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsi Week9</title>
    <style>
        #kumpulan-item div {
            width: 30%;
            float: left;
            padding: 10px 0;
            background-color: orange;
            margin: 1%;
            text-align: center;
            height: 50px;
        }

        #div-tombol-show {
            clear: both;
            text-align: center;
        }

        #div-tombol-show button {
            background: none;
            background-color: orange;
            border: none;
            padding: 10px;
            border-radius: 5%;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php 
        $sql = "SELECT judul FROM movie ORDER BY judul ASC LIMIT 0,3";
        $result = $connection->query($sql);
    ?>
    <div id="kumpulan-item">
        <?php 
        while($row = $result->fetch_assoc()){
            // var_dump($row['judul']);
            echo "<div>".$row['judul']."</div>";
        }
        ?>
    </div>
    <div>
        <div id="div-tombol-show">
            <button type='button' id="show" next-offset="3">Show More</button>
        </div>
    </div>
</body>
<script src="jquery-3.5.1.min.js"></script>
<script>
    // $('#show').click(function () {
    //     var next_offset = parseInt($('#show').attr('next-offset'));
    //     $.ajax({
    //         url: 'getitem.php',
    //         method: 'post',
    //         data: {
    //             offset: next_offset
    //         },
    //         dataType: 'JSON',
    //         success: function (data) {
    //             console.log(data);
    //             data.forEach(e => {
    //                 $('#kumpulan-item').append("<div class='div-ajax'>" + e.judul +
    //                     "</div>");
    //             });
    //             next_offset += 3;
    //             $('#show').attr('next-offset', next_offset);
    //         }
    //     })
    // })
    $('#show').click(function () {
        var text = $('#show').html();
        if (text == 'Show More') {
            var next_offset = parseInt($('#show').attr('next-offset'));
            $.ajax({
                url: 'getitem.php',
                method: 'post',
                data: {
                    offset: next_offset
                },
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    if (data.length == 0) {
                        $('#show').html('Hide Data');
                        $('#show').attr('next-offset', 3);
                    } else {
                        data.forEach(e => {
                            $('#kumpulan-item').append("<div class='div-ajax'>" + e.judul +
                                "</div>");
                        });
                        next_offset += 3;
                        $('#show').attr('next-offset', next_offset);
                    }
                }
            })
        } else {
            $('.div-ajax').remove();
            $('#show').html('Show More');
        }
    })
</script>

</html>
<?php 
$connection->close();
?>