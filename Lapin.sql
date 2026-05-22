use Lapin;
/*CREATE TABLE Zone(
   Id_Zone INT AUTO_INCREMENT,
   Nom VARCHAR(50),
   Adresse VARCHAR(50),
   PRIMARY KEY(Id_Zone)
);

CREATE TABLE Jardins(
   Id_Jardin INT AUTO_INCREMENT,
   Nom VARCHAR(50),
   Adresse VARCHAR(50),
   Id_Zone INT NOT NULL,
   PRIMARY KEY(Id_Jardin),
   FOREIGN KEY(Id_Zone) REFERENCES Zone(Id_Zone)
);

CREATE TABLE Collecte(
   Id_Collecte INT AUTO_INCREMENT,
   dateCollecte DATE,
   datePrevisionnelle DATE,
   PRIMARY KEY(Id_Collecte)
);

CREATE TABLE Chocolat(
   Id_Chocolat INT AUTO_INCREMENT,
   typeChocolat VARCHAR(50),
   PRIMARY KEY(Id_Chocolat)
);

CREATE TABLE Utilisateurs(
   Id_Utilisateurs INT AUTO_INCREMENT,
   Nom VARCHAR(50),
   Email VARCHAR(50),
   MDP VARCHAR(255),
   Role VARCHAR(50),
   Params JSON,
   PRIMARY KEY(Id_Utilisateurs)
);

CREATE TABLE Log(
   Id_Log INT AUTO_INCREMENT,
   Date_Heure DATETIME,
   PRIMARY KEY(Id_Log)
);

CREATE TABLE Magasins(
   Id_Magasins INT AUTO_INCREMENT,
   Nom VARCHAR(50),
   Adresse VARCHAR(50),
   Id_Utilisateurs INT NOT NULL,
   PRIMARY KEY(Id_Magasins),
   FOREIGN KEY(Id_Utilisateurs) REFERENCES Utilisateurs(Id_Utilisateurs)
);

CREATE TABLE Lapins(
   Id_Lapins INT AUTO_INCREMENT,
   Nom VARCHAR(50),
   Couleur VARCHAR(50),
   Statut VARCHAR(50),
   Id_Utilisateurs INT NOT NULL,
   PRIMARY KEY(Id_Lapins),
   FOREIGN KEY(Id_Utilisateurs) REFERENCES Utilisateurs(Id_Utilisateurs)
);

CREATE TABLE Livraisons(
   Id_Livraison INT AUTO_INCREMENT,
   dateLivraison DATE,
   quantiteLivraison INT,
   datePrevisionnelle DATE,
   Statut VARCHAR(50),
   Id_Utilisateurs INT NOT NULL,
   Id_Zone INT NOT NULL,
   Id_Lapins INT NOT NULL,
   PRIMARY KEY(Id_Livraison),
   FOREIGN KEY(Id_Utilisateurs) REFERENCES Utilisateurs(Id_Utilisateurs),
   FOREIGN KEY(Id_Zone) REFERENCES Zone(Id_Zone),
   FOREIGN KEY(Id_Lapins) REFERENCES Lapins(Id_Lapins)
);

CREATE TABLE possede(
   Id_Magasins INT AUTO_INCREMENT,
   Id_Collecte INT,
   PRIMARY KEY(Id_Magasins, Id_Collecte),
   FOREIGN KEY(Id_Magasins) REFERENCES Magasins(Id_Magasins),
   FOREIGN KEY(Id_Collecte) REFERENCES Collecte(Id_Collecte)
);

CREATE TABLE effectue(
   Id_Lapins INT AUTO_INCREMENT,
   Id_Collecte INT,
   PRIMARY KEY(Id_Lapins, Id_Collecte),
   FOREIGN KEY(Id_Lapins) REFERENCES Lapins(Id_Lapins),
   FOREIGN KEY(Id_Collecte) REFERENCES Collecte(Id_Collecte)
);

CREATE TABLE concerne(
   Id_Collecte INT AUTO_INCREMENT,
   Id_Chocolat INT AUTO_INCREMENT,
   PRIMARY KEY(Id_Collecte, Id_Chocolat),
   FOREIGN KEY(Id_Collecte) REFERENCES Collecte(Id_Collecte),
   FOREIGN KEY(Id_Chocolat) REFERENCES Chocolat(Id_Chocolat)
);

CREATE TABLE contient(
   Id_Livraison INT AUTO_INCREMENT,
   Id_Chocolat INT AUTO_INCREMENT,
   Quantite INT,
   PRIMARY KEY(Id_Livraison, Id_Chocolat),
   FOREIGN KEY(Id_Livraison) REFERENCES Livraisons(Id_Livraison),
   FOREIGN KEY(Id_Chocolat) REFERENCES Chocolat(Id_Chocolat)
);

CREATE TABLE genere(
   Id_Livraison INT AUTO_INCREMENT,
   Id_Log INT AUTO_INCREMENT,
   PRIMARY KEY(Id_Livraison, Id_Log),
   FOREIGN KEY(Id_Livraison) REFERENCES Livraisons(Id_Livraison),
   FOREIGN KEY(Id_Log) REFERENCES Log(Id_Log)
);

CREATE TABLE enregistre(
   Id_Jardin INT AUTO_INCREMENT,
   Id_Log INT AUTO_INCREMENT,
   PRIMARY KEY(Id_Jardin, Id_Log),
   FOREIGN KEY(Id_Jardin) REFERENCES Jardins(Id_Jardin),
   FOREIGN KEY(Id_Log) REFERENCES Log(Id_Log)
);

CREATE TABLE affecte_a(
   Id_Lapins INT AUTO_INCREMENT,
   Id_Jardin INT AUTO_INCREMENT,
   PRIMARY KEY(Id_Lapins, Id_Jardin),
   FOREIGN KEY(Id_Lapins) REFERENCES Lapins(Id_Lapins),
   FOREIGN KEY(Id_Jardin) REFERENCES Jardins(Id_Jardin)
);*/