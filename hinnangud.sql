CREATE TABLE hinnangud (
   
    id INT AUTO_INCREMENT PRIMARY KEY,
    kasutajanimi VARCHAR(100),
    kommentaar TEXT,
    hinnang INT,
    FOREIGN KEY (asutused_id) REFERENCES asutused(id)
);
