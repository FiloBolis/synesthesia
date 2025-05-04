<?php
class Database
{
    private static $instance = null;
    private $conn;

    // Configurazione DB
    private $host     = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'synesthesia';

    // Costruttore privato
    private function __construct()
    {
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if ($this->conn->connect_error) {
            die("Connessione fallita: " . $this->conn->connect_error);
        }
    }

    // Metodo per ottenere l'istanza
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Metodo per ottenere la connessione
    public function getConn()
    {
        return $this->conn;
    }

    public function doLogin($username, $password)
    {
        require_once "../class/Utente.php";
        $query = "SELECT * FROM utenti WHERE username = ? AND password = ?";
        $stmt  = $this->conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return false;
        }

        if (! isset($_SESSION)) {
            session_start();
        }

        $id = $result->fetch_assoc()["id"];
        $_SESSION["user"] = new Utente($id);
        return true;
    }

    public function doRegistrazione($username, $password, $email)
    {
        $query = "INSERT INTO utenti (username, email, password) VALUES (?, ?, ?)";
        $stmt  = $this->conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getGeneriMusicali()
    {
        $generi = [];
        $query  = "SELECT * FROM generi_musicali";
        $result = $this->conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $generi[] = $row["nome"];
        }
        return $generi;
    }

    public function getStili()
    {
        $stili  = [];
        $query  = "SELECT * FROM stili";
        $result = $this->conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $stili[] = $row["nome"];
        }
        return $stili;
    }

    public function editProfilo($id, $username, $email, $bio, $genere, $stile) {
        $username_attuale = $this->getUsername($id);
        $email_attuale = $this->getEmail($id);
        $bio_attuale = $this->getBio($id);
        $genere_attuale = $this->getGenere($id);
        $stile_attuale = $this->getStile($id);

        if($username_attuale == $username && $email_attuale == $email && $bio_attuale == $bio && $genere_attuale == $genere && $stile_attuale == $stile)
            return false;
        
        $this->setUsername($username, $id);
        $this->setEmail($email, $id);
        $this->setBio($bio, $id);
        $this->setGenere($genere, $id);
        $this->setStile($stile, $id);
        return true;
    }

    //metodi per ottenere le info utente
    public function getUsername($id) {
        $query = "SELECT username FROM utenti WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["username"];            
    }

    public function getEmail($id) {
        $query = "SELECT email FROM utenti WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["email"];            
    }

    public function getBio($id) {
        $query = "SELECT biografia FROM utenti WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["biografia"];
    }

    public function getGenere($id) {
        $query = "SELECT nome FROM generi_musicali WHERE id = (SELECT id_musica FROM utenti WHERE id = ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row["nome"] : null;
    }

    public function getStile($id) {
        $query = "SELECT nome FROM stili WHERE id = (SELECT id_stile FROM utenti WHERE id = ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row["nome"] : null;
    }

    //metodi per modificare l'utente
    public function setUsername($username, $id) {
        $query = "UPDATE utenti SET username = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();
    }

    public function setEmail($email, $id) {
        $query = "UPDATE utenti SET email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
    }

    public function setBio($bio, $id) {
        $query = "UPDATE utenti SET biografia = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $bio, $id);
        $stmt->execute();
    }

    public function setStile($stile, $id) {
        if($stile == null)
            return;
        $query = "UPDATE utenti SET id_stile = (SELECT id FROM stili WHERE nome = ?) WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $stile, $id);
        $stmt->execute();
    }

    public function setGenere($genere, $id) {
        if($genere == null)
            return;
        $query = "UPDATE utenti SET id_musica = (SELECT id FROM generi_musicali WHERE nome = ?) WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $genere, $id);
        $stmt->execute();
    }

    public function getVestiti() {
        require_once "../class/Vestito.php";

        $query = "SELECT * FROM vestiti";
        $result = $this->conn->query($query);
        $vestiti = [];
        while($row = $result->fetch_assoc()) {
            $vestiti[] = new Vestito(
                $row["id"],
                $row["id_utente"],
                $row["categoria"],
                $row["id_tipo"],
                $row["colore"],
                $row["id_stile"],
                $row["materiale"],
                $row["vestibilita"],
                $row["descrizione"],
                $row["img_path"],
                $row["nome"]
            );
        }
        return $vestiti;
    }

    public function getStileVestito($vestito) {
        require_once "../class/Vestito.php";
        $id_stile = $vestito->getIdStile();
        $query = "SELECT nome FROM tipo_vestiti WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_stile);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row["nome"] : null;
    }

    public function getTipoVestito($vestito) {
        require_once "../class/Vestito.php";
        $id_stile = $vestito->getIdTipo();
        $query = "SELECT nome FROM stili WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_stile);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row["nome"] : null;
    }

    public function addCapo($nome, $categoria, $colore, $stile, $materiale, $vestibilita, $descrizione, $imagePath, $id_utente) {
        $query = "SELECT id FROM stili WHERE nome = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $stile);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $id_stile = $row ? $row["id"] : null;

        if ($id_stile == null) {
            return false; // Stile non trovato
        }
        $query = "INSERT INTO vestiti (id_utente, nome, categoria, id_tipo, colore, id_stile, materiale, vestibilita, descrizione, img_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issississs", $id_utente, $nome, $categoria, 1, $colore, $id_stile, $materiale, $vestibilita, $descrizione, $imagePath);
        if ($stmt->execute()) {
            return true; // Capo aggiunto con successo
        } else {
            return false; // Errore durante l'aggiunta del capo
        }
    }

    public function deleteCapo($id) {
        $query = "DELETE FROM vestiti WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return true; // Capo eliminato con successo
        } else {
            return false; // Errore durante l'eliminazione del capo
        }
    }

    public function getVettVestiti($idUtente) {
        require_once "../class/Vestito.php";
        $query = "SELECT * FROM vestiti WHERE id_utente = ?";
        $stmt  = $this->conn->prepare($query);
        $stmt->bind_param("i", $idUtente);
        $stmt->execute();
        $result = $stmt->get_result();
        $vestiti = [];

        while ($row = $result->fetch_assoc()) {
            $vestiti[] = [
                "id"          => $row["id"],
                "categoria"   => $row["categoria"],
                "tipo"        => $this->getTipoVestito(new Vestito(
                    $row["id"],
                    $row["id_utente"],
                    $row["categoria"],
                    $row["id_tipo"],
                    $row["colore"],
                    $row["id_stile"],
                    $row["materiale"],
                    $row["vestibilita"],
                    $row["descrizione"],
                    $row["img_path"],
                    $row["nome"]
                )),
                "colore"      => $row["colore"],
                "stile"       => $this->getStileVestito(new Vestito(
                    $row["id"],
                    $row["id_utente"],
                    $row["categoria"],
                    $row["id_tipo"],
                    $row["colore"],
                    $row["id_stile"],
                    $row["materiale"],
                    $row["vestibilita"],
                    $row["descrizione"],
                    $row["img_path"],
                    $row["nome"]
                )),
                "materiale"   => $row["materiale"],
                "vestibilita" => $row["vestibilita"],
                "descrizione" => $row["descrizione"],
                "img_path"    => $row["img_path"],
                "nome"        => $row["nome"]
            ];
        }
        return $vestiti;
    }

    public function suggerisciAbbigliamento($stagione, $temperatura, $umidita, $vento, $codice_meteo, $stile, $idUtente) {
        require_once "../class/OutfitFunzioni.php";

        $vestiti_disponibili = $this->getVettVestiti($idUtente);

        // Analizza condizioni meteo
        $condizioni_meteo = OutfitFunzioni::analizzaMeteo($codice_meteo);
        
        // Calcola temperatura percepita (considerando vento e umidità)
        $temperatura_percepita = OutfitFunzioni::calcolaTemperaturaPercepita($temperatura, $umidita, $vento);
        
        // Determina esigenze base in base alla temperatura e condizioni
        $esigenze = OutfitFunzioni::determinaEsigenze($temperatura_percepita, $condizioni_meteo, $stagione);
        
        // Trova i capi più adatti dal guardaroba disponibile
        $vestiti = OutfitFunzioni::trovaCapiAdatti($vestiti_disponibili, $esigenze, $stile);

        // Crea outfit in base ai vestiti ottenuti
        $outfit = OutfitFunzioni::creaOutfit($esigenze, $vestiti);
        
        return $outfit;
    }
}