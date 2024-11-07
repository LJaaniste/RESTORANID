CREATE TABLE hinnangud (
    asutused_id INT,
    kasutajanimi VARCHAR(100),
    kommentaar TEXT,
    hinnang INT,
    FOREIGN KEY (asutused_id) REFERENCES asutused(id)
);
