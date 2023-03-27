<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php
$c = mysqli_connect('localhost', 'root', '', 'invoice_system');
$arr = [103, 106, 113];
?>
total:<input type="text" id="sumTotal" value="0">

<table>
    <tr>
        <td>id</td>
        <td>name</td>
        <td>price</td>
        <td>qty</td>
        <td>total</td>
    </tr>
    <?php
    foreach ($arr as $each) {
        $q = "
        SELECT * from items where code=$each";
        $data = mysqli_query($c, $q);
        $row = mysqli_fetch_assoc($data)
    ?>
        <tr>
            <td>
                <p><?php echo $row['code']; ?></p>
            </td>
            <td>
                <p><?php echo $row['name']; ?></p>
            </td>
            <td>
                <input class="price" type="text" value="<?php echo $row['price']; ?>">
            </td>
            <td class="qty_div"><input class="qty" type="text"></td>
            <td>
                <input type="text" class="total" value="0">
            </td>
        </tr>

    <?php
    }

    ?>
</table>
<script>
    // $(".qty_div").keyup(function() {
    //     var price = $(this).prev('td').find('.price').val();


    //     var qty = $(this).find('.qty').val();

    //     let tot = parseInt(price) * parseInt(qty);
    //     $(this).next('td').find('.total').val(tot);
    //     var total = $(this).next('td').find('.total').val();
    //     console.log(total);
    //     var sumTotal = document.getElementsByClassName("total");
    //     //  console.log(sumTotal[0].value);

    //     var sum = 0;
    //     for (var i = 0; i < sumTotal.length; i++) {
    //         sum = parseInt(sum) + parseInt(sumTotal[i].value);
    //     }
    //     $('#sumTotal').val(sum);
    // })
    $(".qty").keyup(function() {
        var price = $(this).parent('td').prev('td').find('.price').val();


        var qty = $(this).val();

        let tot = parseInt(price) * parseInt(qty);
        $(this).parent('td').next('td').find('.total').val(tot);
        // var total = $(this).next('td').find('.total').val();
        // console.log(total);
        var sumTotal = document.getElementsByClassName("total");
        //  console.log(sumTotal[0].value);

        var sum = 0;
        for (var i = 0; i < sumTotal.length; i++) {
            sum = parseInt(sum) + parseInt(sumTotal[i].value);
        }
        $('#sumTotal').val(sum);
    })
</script>