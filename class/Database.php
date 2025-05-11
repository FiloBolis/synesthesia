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

    public function getTipi() {
        $tipi  = [];   
        $query  = "SELECT * FROM tipo_vestiti";
        $result = $this->conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $tipi[] = $row["nome"];
        }
        return $tipi;
    }

    public function getMateriali() {
        $materiali  = [];   
        $query  = "SELECT * FROM materiali";
        $result = $this->conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $materiali[] = $row["nome"];
        }
        return $materiali;
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

    public function getVestiti($id_utente) {
        require_once "../class/Vestito.php";

        $query = "SELECT * FROM vestiti WHERE id_utente = ?";
        $stmt  = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_utente);
        $stmt->execute();
        $result = $stmt->get_result();
        $vestiti = [];
        while($row = $result->fetch_assoc()) {
            $vestiti[] = new Vestito(
                $row["id"],
                $row["id_utente"],
                $row["categoria"],
                $row["id_tipo"],
                $row["colore"],
                $row["id_stile"],
                $row["id_materiale"],
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
        $query = "SELECT nome FROM stili WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_stile);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row["nome"] : null;
    }

    public function getTipoVestito($vestito) {
        require_once "../class/Vestito.php";
        $id_tipo = $vestito->getIdTipo();
        $query = "SELECT nome FROM tipo_vestiti WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_tipo);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row["nome"] : null;
    }

    public function getMaterialeVestito($vestito) {
        require_once "../class/Vestito.php";
        $id_materiale = $vestito->getIdMateriale();
        $query = "SELECT nome FROM materiali WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_materiale);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row["nome"] : null;
    }

    public function getStratoUpper($tipo) {
        $query = "SELECT strato FROM tipo_vestiti WHERE nome = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $tipo);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["strato"];
    }

    public function addCapo($nome, $categoria, $colore, $stile, $materiale, $tipo, $vestibilita, $descrizione, $imagePath, $id_utente) {
        $id_stile = $this->getIdStile($stile);
        $id_materiale = $this->getIdMateriale($materiale);
        $id_tipo = $this->getIdTipo($tipo);

        if ($id_stile == null) {
            return false; // Stile non trovato
        }
        $query = "INSERT INTO vestiti (id_utente, nome, categoria, id_tipo, colore, id_stile, id_materiale, vestibilita, descrizione, img_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issisiisss", $id_utente, $nome, $categoria, $id_tipo, $colore, $id_stile, $id_materiale, $vestibilita, $descrizione, $imagePath);
        if ($stmt->execute()) {
            return true; // Capo aggiunto con successo
        } else {
            return false; // Errore durante l'aggiunta del capo
        }
    }

    public function getIdStile($stile) {
        $query = "SELECT id FROM stili WHERE nome = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $stile);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["id"];
    }

    public function getIdMateriale($materiale) {
        $query = "SELECT id FROM materiali WHERE nome = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $materiale);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["id"];
    }

    public function getIdTipo($tipo) {
        $query = "SELECT id FROM tipo_vestiti WHERE nome = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $tipo);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row["id"];
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
            if($row["categoria"] == "top") {
                $tipo = $this->getTipoVestito(new Vestito(
                    $row["id"],
                    $row["id_utente"],
                    $row["categoria"],
                    $row["id_tipo"],
                    $row["colore"],
                    $row["id_stile"],
                    $row["id_materiale"],
                    $row["vestibilita"],
                    $row["descrizione"],
                    $row["img_path"],
                    $row["nome"]
                ));
                $vestiti[] = [
                    "id"          => $row["id"],
                    "categoria"   => $row["categoria"],
                    "tipo"        => $tipo,
                    "colore"      => $row["colore"],
                    "stile"       => $this->getStileVestito(new Vestito(
                        $row["id"],
                        $row["id_utente"],
                        $row["categoria"],
                        $row["id_tipo"],
                        $row["colore"],
                        $row["id_stile"],
                        $row["id_materiale"],
                        $row["vestibilita"],
                        $row["descrizione"],
                        $row["img_path"],
                        $row["nome"]
                    )),
                    "materiale"   => $this->getMaterialeVestito(new Vestito(
                        $row["id"],
                        $row["id_utente"],
                        $row["categoria"],
                        $row["id_tipo"],
                        $row["colore"],
                        $row["id_stile"],
                        $row["id_materiale"],
                        $row["vestibilita"],
                        $row["descrizione"],
                        $row["img_path"],
                        $row["nome"]
                    )),
                    "vestibilita" => $row["vestibilita"],
                    "descrizione" => $row["descrizione"],
                    "img_path"    => $row["img_path"],
                    "nome"        => $row["nome"],
                    "strato" => $this->getStratoUpper($tipo)
                ];
            } else {
                $tipo = $this->getTipoVestito(new Vestito(
                    $row["id"],
                    $row["id_utente"],
                    $row["categoria"],
                    $row["id_tipo"],
                    $row["colore"],
                    $row["id_stile"],
                    $row["id_materiale"],
                    $row["vestibilita"],
                    $row["descrizione"],
                    $row["img_path"],
                    $row["nome"]
                ));
                $vestiti[] = [
                    "id"          => $row["id"],
                    "categoria"   => $row["categoria"],
                    "tipo"        => $tipo,
                    "colore"      => $row["colore"],
                    "stile"       => $this->getStileVestito(new Vestito(
                        $row["id"],
                        $row["id_utente"],
                        $row["categoria"],
                        $row["id_tipo"],
                        $row["colore"],
                        $row["id_stile"],
                        $row["id_materiale"],
                        $row["vestibilita"],
                        $row["descrizione"],
                        $row["img_path"],
                        $row["nome"]
                    )),
                    "materiale"   => $this->getMaterialeVestito(new Vestito(
                        $row["id"],
                        $row["id_utente"],
                        $row["categoria"],
                        $row["id_tipo"],
                        $row["colore"],
                        $row["id_stile"],
                        $row["id_materiale"],
                        $row["vestibilita"],
                        $row["descrizione"],
                        $row["img_path"],
                        $row["nome"]
                    )),
                    "vestibilita" => $row["vestibilita"],
                    "descrizione" => $row["descrizione"],
                    "img_path"    => $row["img_path"],
                    "nome"        => $row["nome"]
                ];
            }
        }
        return $vestiti;
    }

    public function suggerisciAbbigliamento($stagione, $temperatura, $umidita, $vento, $codice_meteo, $idUtente) {
        require_once "../class/OutfitFunzioni.php";

        $vestiti_disponibili = $this->getVettVestiti($idUtente);

        // Analizza condizioni meteo
        $condizioni_meteo = OutfitFunzioni::analizzaMeteo($codice_meteo);
        
        // Calcola temperatura percepita (considerando vento e umidità)
        $temperatura_percepita = OutfitFunzioni::calcolaTemperaturaPercepita($temperatura, $umidita, $vento);
        
        // Determina esigenze base in base alla temperatura e condizioni
        $esigenze = OutfitFunzioni::determinaEsigenze($temperatura_percepita, $condizioni_meteo, $stagione);
        
        // Trova i capi più adatti dal guardaroba disponibile
        $vestiti = OutfitFunzioni::trovaCapiAdatti($vestiti_disponibili, $esigenze);

        // Crea outfit in base ai vestiti ottenuti
        $outfit = OutfitFunzioni::creaOutfit($esigenze, $vestiti);
        
        return $outfit;
    }

    public function addActivity($id_utente, $attivita) {
        $query = "INSERT INTO attivita (id_utente, nome) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("is", $id_utente, $attivita);
        $stmt->execute();
    }

    public function getActivities($id_utente) {
        $query = "SELECT * FROM attivita WHERE id_utente = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_utente);
        $stmt->execute();
        $result = $stmt->get_result();

        $attivita = [];
        while($row = $result->fetch_assoc())
            $attivita[] = $row;

        return $attivita;
    }
}