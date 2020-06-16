<?php

    $ini = parse_ini_file('config.ini');
    $link = mysqli_connect($ini['db_host'], $ini['db_user'], $ini['db_password']);
    $database = mysqli_select_db($link, $ini['db_name']);

    $user = $_GET['user_email'];
    $hwid = trim($_GET['hwid']);

    $tables = $ini['FullRecoilPubg'];
    $data_current = date("Ymd");
    $valid = false;

    $arrProduct = [26377];

    if (empty($hwid)) {
        echo 0;
    } else {
        $result = getInfosPorEmail($tables, $link, $user, $arrProduct);

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                if ($row['access_expires'] != null && $row['access_expires'] < $data_current) {
                    echo "404";
                } else {
                    if (!empty($row['hwid'])) {
                        if ($hwid != trim($row['hwid'])) {
                            echo 504; // hwid errado
                        } else {
                            echo 4;
                            $valid = true;
                        }
                    } else {
                        $valid = updateHwid($tables, $hwid, $user, $link);
                    }
                }

                if(in_array($row['product_id'], $arrProduct)){
                    if ($valid) {
                        echo 3; //Possui apenas um jogo
                    }
                }
            }

        } else {
            echo "604"; // EMAIL ERRADO
        }

    }

    /**
     * Retorna os dados do produto por Email.
     *
     * @param $tables
     * @param mysqli $link
     * @param $user
     * @param $arrProduct
     * @return bool|mysqli_result
     */
    function getInfosPorEmail($tables, mysqli $link, $user, $arrProduct)
    {
        $sql = "SELECT product_id, user_email, hwid, max(access_expires) as acess_expires FROM {$tables}";
        $sql .= " WHERE user_email = '" . mysqli_real_escape_string($link, $user) . "'";
        $sql .= " AND product_id in(" . implode("," , $arrProduct ) . ")";
        $sql .= " GROUP BY product_id, user_email";
        return $link->query($sql);
    }

    /**
     * Faz o Update do Hwid de acordo com os parametros informados.
     *
     * @param $tables
     * @param string $hwid
     * @param $user
     * @param mysqli $link
     * @return bool
     */
    function updateHwid($tables, string $hwid, $user, mysqli $link)
    {
        $sql = "UPDATE " . $tables . " SET hwid='$hwid' WHERE user_email='$user'";

        if (mysqli_query($link, $sql)) {
            $valid = true;
            echo 5;
        } else {
            echo 2;
        }

        return $valid;
    }
?>
