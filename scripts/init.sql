CREATE TABLE IF NOT EXISTS offre (
    id INT PRIMARY KEY,
    url TEXT NOT NULL,
    intitule TEXT NOT NULL,
    description TEXT NOT NULL,
    typeContrat TEXT NOT NULL,
    nomEntreprise TEXT NOT NULL,
    dateCreation DATETIME,
    dateActualisation DATETIME
);