DROP TABLE IF EXISTS "testCollection";
CREATE TABLE "testCollection" (
"id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
"propertyA" INTEGER,
"propertyB" TEXT
);
INSERT INTO "testCollection" VALUES (1, 1, 'a');
INSERT INTO "testCollection" VALUES (2, 2, 'b');
INSERT INTO "testCollection" VALUES (3, 3, 'c');
INSERT INTO "testCollection" VALUES (4, 4, 'd');
INSERT INTO "testCollection" VALUES (5, 5, 'e');
INSERT INTO "testCollection" VALUES (6, 6, 'f');
INSERT INTO "testCollection" VALUES (7, 1, 'A');
INSERT INTO "testCollection" VALUES (8, 2, 'B');
INSERT INTO "testCollection" VALUES (9, 3, 'C');
INSERT INTO "testCollection" VALUES (10, 4, 'D');
INSERT INTO "testCollection" VALUES (11, 5, 'E');
INSERT INTO "testCollection" VALUES (12, 6, 'F');
