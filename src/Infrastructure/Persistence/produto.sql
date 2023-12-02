CREATE TABLE IF NOT EXISTS produto (
    id INTEGER PRIMARY KEY,
    nome TEXT NOT NULL,
    valor TEXT NOT NULL,
    subcategoria INTEGER NOT NULL,
    FOREIGN KEY(subcategoria) REFERENCES subcategoria(id)
);