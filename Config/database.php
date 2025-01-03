<?php
class Database
{
    private static $uri = "mysql://avnadmin:AVNS_OGjIpkUMvd0GqM2b176@concessionaria-trabalho001.f.aivencloud.com:14594/defaultdb?ssl-mode=REQUIRED";
    private static $fields;
    private static $conn;

    // Inicializa os campos do URI
    private static function parseUri()
    {
        if (!self::$fields) {
            self::$fields = parse_url(self::$uri);
        }
    }

    public static function getConnection()
    {
        self::parseUri(); // Garante que $fields está configurado

        if (self::$conn === null) {
            try {
                $dsn = "mysql:host=" . self::$fields["host"];
                $dsn .= ";port=" . self::$fields["port"];
                $dsn .= ";dbname=defaultdb";
                $dsn .= ";sslmode=verify-ca;sslrootcert=" . __DIR__ . "/ca.pem"; // Certificado no mesmo diretório

                self::$conn = new PDO($dsn, self::$fields["user"], self::$fields["pass"]);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Testa a conexão
                $stmt = self::$conn->query("SELECT VERSION()");

            } catch (PDOException $exception) {
                die("Erro na conexão: " . $exception->getMessage());
            }
        }

        return self::$conn;
    }

    public static function closeConnection()
    {
        self::$conn = null;
    }
}