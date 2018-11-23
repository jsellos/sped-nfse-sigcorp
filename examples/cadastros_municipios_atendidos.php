<?php

function squashCharacters($input)
{
    $input = trim($input);
    $aFind = ['á','à','ã','â','é','ê','í','ó','ô','õ','ú','ü',
        'ç','Á','À','Ã','Â','É','Ê','Í','Ó','Ô','Õ','Ú','Ü','Ç', "'"];
    $aSubs = ['a','a','a','a','e','e','i','o','o','o','u','u',
        'c','A','A','A','A','E','E','I','O','O','O','U','U','C', " "];
    return str_replace($aFind, $aSubs, $input);
}

function seekIbge($ibge, $uf, $name)
{
    foreach ($ibge as $i) {
        if ($i[0] == $uf && $i[1] == $name) {
            return $i[2];
        }
    }
    return '0000000';
}

$uflist = [
        12=>'AC',
        27=>'AL',
        13=>'AM',
        91=>'AN',
        16=>'AP',
        29=>'BA',
        23=>'CE',
        53=>'DF',
        32=>'ES',
        52=>'GO',
        21=>'MA',
        31=>'MG',
        50=>'MS',
        51=>'MT',
        15=>'PA',
        25=>'PB',
        26=>'PE',
        22=>'PI',
        41=>'PR',
        33=>'RJ',
        24=>'RN',
        11=>'RO',
        14=>'RR',
        43=>'RS',
        42=>'SC',
        28=>'SE',
        35=>'SP',
        17=>'TO'
    ];

$ibge = [];
$handle = fopen("Municípios_IBGE.txt", "r");
while (($line = fgets($handle)) !== false) {
    $l = explode('|', $line);
    $cod = $l[0];
    $cuf = substr($cod, 0, 2);
    $uf = $uflist[$cuf];
    $name = strtoupper(squashCharacters(iconv('ISO-8859-1', 'UTF-8', $l[1])));
    array_push($ibge, [$uf, $name, $cod]);
}
fclose($handle);

$muns[] = ['Petropolis','RJ'];
$muns[] = ['Mogi Guaçu','SP'];
$muns[] = ['Marilia','SP'];
$muns[] = ['Tremembé','SP'];
$muns[] = ['Valadares','MG'];
$muns[] = ['Rio Grande','RS'];
$muns[] = ['Sarandi','PR'];
$muns[] = ['Botucatu','SP'];
$muns[] = ['Cianorte','PR'];
$muns[] = ['Sao Joao de Meriti','RJ'];
$muns[] = ['Arapoti','PR'];
$muns[] = ['Conceicao do Mato Dentro','MG'];
$muns[] = ['Brumadinho','MG'];
$muns[] = ['Itapira','SP'];
$muns[] = ['Barretos','SP'];
$muns[] = ['Cerqueira Cesar','SP'];

asort($muns);

foreach ($muns as $m) {
    $uf = $m[1];
    $name = strtoupper(squashCharacters($m[0]));
    $codeibge = seekIbge($ibge, $uf, $name);
    $lowname = strtolower(str_replace(' ', '', $name));
    $hom = "https://test$lowname.sigiss.com.br/$lowname/ws/sigiss_ws.php";
    $pro = "https://$lowname.sigiss.com.br/$lowname/ws/sigiss_ws.php";
    $padrao[$codeibge] = [
        'municipio' => $name,
        'uf' => $uf,
        'homologacao' => $hom,
        'producao' => $pro
    ];
}


file_put_contents('municipios_sigcorp.json', json_encode($padrao, JSON_PRETTY_PRINT));
echo "<pre>";
print_r(json_encode($padrao, JSON_PRETTY_PRINT));
echo "<pre>";
