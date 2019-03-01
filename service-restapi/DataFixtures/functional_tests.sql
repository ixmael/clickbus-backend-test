SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE user;
INSERT INTO user VALUES
    (1, 'usuario_1@dominio_a.com',  'usuario 1', NOW(), NOW()),
    (2, 'usuario_2@dominio_a.com',  'usuario 2', NOW(), NOW()),
    (3, 'usuario_3@dominio_a.com',  'usuario 3', NOW(), NOW()),
    (4, 'usuario_4@dominio_a.com',  'usuario 4', NOW(), NOW()),
    (5, 'usuario_5@dominio_a.com',  'usuario 5', NOW(), NOW()),
    (6, 'usuario_6@dominio_a.com',  'usuario 6', NOW(), NOW()),
    (7, 'usuario_7@dominio_a.com',  'usuario 7', NOW(), NOW()),
    (8, 'usuario_8@dominio_a.com',  'usuario 8', NOW(), NOW()),
    (9, 'usuario_9@dominio_a.com',  'usuario 9', NOW(), NOW()),
    (10, 'usuario_10@dominio_a.com', 'usuario 10', NOW(), NOW())
;

TRUNCATE accounts;
INSERT INTO accounts VALUES
    (1, NOW(), NOW(), 'debit', 1000, NULL, 2, NULL, 1000),
    (2, NOW(), NOW(), 'debit', 2000, NULL, 3, NULL, 2000),
    (3, NOW(), NOW(), 'debit', 3000, NULL, 7, NULL, 3000),
    (4, NOW(), NOW(), 'credit', NULL, 3000, 2, 5000, NULL),
    (5, NOW(), NOW(), 'credit', NULL, 2000, 3, 3000, NULL),
    (7, NOW(), NOW(), 'credit', NULL, 1000, 7, 2000, NULL),
    (8, NOW(), NOW(), 'credit', NULL, 1000, 5, 2000, NULL),
    (9, NOW(), NOW(), 'debit', 1000, NULL, 10, NULL, 1000),
    (10, NOW(), NOW(), 'debit', 2500, NULL, 6, NULL, 2500)
;

TRUNCATE transactions;

SET FOREIGN_KEY_CHECKS = 1;
