-- Run once against an existing mellifica database to add stock tracking.
ALTER TABLE `miel`
  ADD COLUMN `stock` INT UNSIGNED NOT NULL DEFAULT 0;
