@extends('template')
@section('title', 'Channel - '.$channel->title)
@section('content')
    <?php
    function escapeJavaScriptText($string)
    {
        return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
    }

    function rc4($key, $str)
    {
        $s = array();
        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = '';
        for ($y = 0; $y < strlen($str); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return $res;
    }

    $javascriptCode = sprintf('jwplayer("container").setup({
                sources: [
                    {
                        file: "rtmp://arsen-sargsyan.info:1935/%s/flv:"
                    }
                ],
                image: "bg.jpg",
                autostart: false,
                width: 720,
                height: 400,
                primary: "flash"
            });',$channel->key);

    $script = stripslashes($javascriptCode);
    $packer = new \App\library\JavaScriptPacker($script, 62, true, true);
    $packedJsCode = $packer->pack();
    ?>
    <script src="{{url('jwplayer/jwplayer.js')}}"></script>
    <div id="container">Loading the player ...</div>
    <script type="text/javascript">
        <?=$packedJsCode?>
    </script>
@stop
