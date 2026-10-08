ALTER TABLE `anamnesis`
  MODIFY `motivo_consulta` varchar(200) DEFAULT NULL,
  MODIFY `inicio_enfermedad` date DEFAULT NULL,
  MODIFY `examenes_efectuados` varchar(200) DEFAULT NULL,
  MODIFY `tratamientos_realizados` varchar(200) DEFAULT NULL,
  MODIFY `ult_desp_interna` date DEFAULT NULL,
  MODIFY `ult_desp_int_producto` varchar(80) DEFAULT NULL,
  MODIFY `ult_desp_externa` date DEFAULT NULL,
  MODIFY `ult_desp_ext_producto` varchar(80) DEFAULT NULL,
  MODIFY `ultima_vacunacion` date DEFAULT NULL,
  MODIFY `tipo_alimentacion` varchar(100) DEFAULT NULL,
  MODIFY `frec_alimentacion` varchar(100) DEFAULT NULL,
  MODIFY `apetito` varchar(100) DEFAULT NULL,
  MODIFY `ingesta_agua` varchar(100) DEFAULT NULL,
  MODIFY `contacto_animal` varchar(100) DEFAULT NULL,
  MODIFY `vomito` varchar(100) DEFAULT NULL,
  MODIFY `heces` varchar(100) DEFAULT NULL,
  MODIFY `miccion` varchar(100) DEFAULT NULL,
  MODIFY `fch_ultimo_celo` date DEFAULT NULL,
  MODIFY `prod_higiene` varchar(80) DEFAULT NULL,
  MODIFY `frec_higiene` varchar(100) DEFAULT NULL,
  MODIFY `int_ambiente` varchar(100) DEFAULT NULL,
  MODIFY `ext_ambiente` varchar(100) DEFAULT NULL,
  MODIFY `act_ectoparasitos` tinyint(1) DEFAULT NULL,
  MODIFY `ant_ectoparasitos` tinyint(1) DEFAULT NULL;

ALTER TABLE `examen_clinico`
  MODIFY `peso` decimal(5,2) DEFAULT NULL,
  MODIFY `cc` int(1) DEFAULT NULL,
  MODIFY `temp_celsius` decimal(4,1) DEFAULT NULL,
  MODIFY `pulso_yugular` varchar(80) DEFAULT NULL,
  MODIFY `fr_rpm` int(11) DEFAULT NULL,
  MODIFY `fc_lpm` int(11) DEFAULT NULL,
  MODIFY `pulso_ppm` int(11) DEFAULT NULL,
  MODIFY `tlc_seg` int(11) DEFAULT NULL,
  MODIFY `tpc_seg` int(11) DEFAULT NULL,
  MODIFY `pas` varchar(40) DEFAULT NULL,
  MODIFY `pad` varchar(40) DEFAULT NULL,
  MODIFY `prcnt_deshidratacion` decimal(4,1) DEFAULT NULL,
  MODIFY `gangliios_palpables` varchar(100) DEFAULT NULL,
  MODIFY `mucosas_visibles` varchar(100) DEFAULT NULL,
  MODIFY `ectoparasitos` varchar(100) DEFAULT NULL,
  MODIFY `actitud` varchar(80) DEFAULT NULL,
  MODIFY `hallazgos` varchar(400) DEFAULT NULL;

ALTER TABLE `resultado_laboratorio`
  MODIFY `hematologia_completa` varchar(300) DEFAULT NULL,
  MODIFY `coprologia` varchar(300) DEFAULT NULL,
  MODIFY `quimica_sanguinea` varchar(300) DEFAULT NULL,
  MODIFY `uro_sangre` varchar(50) DEFAULT NULL,
  MODIFY `uro_urob` varchar(50) DEFAULT NULL,
  MODIFY `uro_bli` varchar(50) DEFAULT NULL,
  MODIFY `uro_prot` varchar(50) DEFAULT NULL,
  MODIFY `uro_nitritos` varchar(50) DEFAULT NULL,
  MODIFY `uro_cetona` varchar(50) DEFAULT NULL,
  MODIFY `uro_glucosa` varchar(50) DEFAULT NULL,
  MODIFY `uro_ph` varchar(50) DEFAULT NULL,
  MODIFY `uro_leu` varchar(50) DEFAULT NULL,
  MODIFY `uro_densidad` varchar(50) DEFAULT NULL,
  MODIFY `uro_microorganismos` varchar(50) DEFAULT NULL,
  MODIFY `uro_celulas` varchar(50) DEFAULT NULL,
  MODIFY `uro_cilindros` varchar(50) DEFAULT NULL,
  MODIFY `uro_cristales` varchar(50) DEFAULT NULL,
  MODIFY `descarte` varchar(50) DEFAULT NULL,
  MODIFY `piel_otros` varchar(50) DEFAULT NULL,
  MODIFY `snap` varchar(50) DEFAULT NULL,
  MODIFY `observaciones` varchar(150) DEFAULT NULL;

ALTER TABLE `recipe`
  DROP FOREIGN KEY `recipe_ibfk_1`;

ALTER TABLE `recipe`
  ADD CONSTRAINT `recipe_consulta_fk` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`);
