<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test t5</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>
<body>
<form action="" id="form" method="post" >
    <table>
        <thead>
            <tr>
                <td><a>N-from</a></td>
                <td><a>N-To</a></td>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <input type="number" min="100000" max="999999" name="start" id="start" ></td>
                <td><input type="number" min="100000" max="999999" name="finish" id="finish"></td>
                <td><input type="button" id="btn" name="submit" value="RUN">
            </td>
            </tr>
            <tr>

            </tr>
        </tbody>
    </table>
    <p>
        Number of tickets : <a id="count"></a>
    </p>
</form>
</body>
</html>

<script>
    $( document ).ready(function() {
        $("#btn").click(function () {
                var params = {};
                params['start'] = $('#start').val();
                params['finish'] = $('#finish').val();
                $.post(
                    'https://skk-studio.com/5g/app.php',
                    params,
                    function (data) {
                        result = $.parseJSON(data);
                        $('#count').html(result['count']);
                    }
                )
            }
        )
    })

</script>