use Lapin;
/*CREATE TABLE Magasins(
   Id_Magasins INT,
   nom VARCHAR(50),
   adresse VARCHAR(50),
   PRIMARY KEY(Id_Magasins)
);

CREATE TABLE lapins(
   Id_lapins INT,
   Nom VARCHAR(50),
   PRIMARY KEY(Id_lapins)
);

CREATE TABLE zone(
   Id_zone INT,
   Nom VARCHAR(50),
   Adresse VARCHAR(50),
   PRIMARY KEY(Id_zone)
);

CREATE TABLE jardins(
   id_jardin INT,
   nom VARCHAR(50),
   Id_zone INT NOT NULL,
   PRIMARY KEY(id_jardin),
   FOREIGN KEY(Id_zone) REFERENCES zone(Id_zone)
);

CREATE TABLE collecte(
   id_collecte INT,
   date_collecte DATE,
   quantite_collecte INT,
   PRIMARY KEY(id_collecte)
);

CREATE TABLE livraisons(
   id_livraison INT,
   date_livraison DATE,
   quantite_livraison INT,
   Id_zone INT NOT NULL,
   PRIMARY KEY(id_livraison),
   FOREIGN KEY(Id_zone) REFERENCES zone(Id_zone)
);

CREATE TABLE chocolat(
   id_chocolat INT,
   type_chocolat VARCHAR(50),
   PRIMARY KEY(id_chocolat)
);

CREATE TABLE Asso_7(
   Id_Magasins INT,
   id_collecte INT,
   PRIMARY KEY(Id_Magasins, id_collecte),
   FOREIGN KEY(Id_Magasins) REFERENCES Magasins(Id_Magasins),
   FOREIGN KEY(id_collecte) REFERENCES collecte(id_collecte)
);

CREATE TABLE Asso_8(
   Id_lapins INT,
   id_collecte INT,
   PRIMARY KEY(Id_lapins, id_collecte),
   FOREIGN KEY(Id_lapins) REFERENCES lapins(Id_lapins),
   FOREIGN KEY(id_collecte) REFERENCES collecte(id_collecte)
);

CREATE TABLE Asso_9(
   Id_lapins INT,
   id_livraison INT,
   PRIMARY KEY(Id_lapins, id_livraison),
   FOREIGN KEY(Id_lapins) REFERENCES lapins(Id_lapins),
   FOREIGN KEY(id_livraison) REFERENCES livraisons(id_livraison)
);

CREATE TABLE Asso_11(
   id_collecte INT,
   id_chocolat INT,
   PRIMARY KEY(id_collecte, id_chocolat),
   FOREIGN KEY(id_collecte) REFERENCES collecte(id_collecte),
   FOREIGN KEY(id_chocolat) REFERENCES chocolat(id_chocolat)
);

CREATE TABLE Asso_12(
   id_livraison INT,
   id_chocolat INT,
   PRIMARY KEY(id_livraison, id_chocolat),
   FOREIGN KEY(id_livraison) REFERENCES livraisons(id_livraison),
   FOREIGN KEY(id_chocolat) REFERENCES chocolat(id_chocolat)
);*/
