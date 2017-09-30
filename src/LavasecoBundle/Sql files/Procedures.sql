/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  diego
 * Created: 30/09/2017
 */


#
Sale Daily Report /////////////////////////////////////////////////////////////

USE `lavaseco_db`;
DROP procedure IF EXISTS `saleDailyReport`;

DELIMITER $$
USE `lavaseco_db`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `saleDailyReport`(IN _from_date DATETIME,IN  _to_date DATETIME,IN  _sale_point_id int)
BEGIN

	IF _sale_point_id = 0 THEN
		select 
			c1.salePoint salePoint,
			sum(c1.total) total,
			c1.fecha fecha,
			sum(c2.cancelado) cancelado,
			sum(c1.total) - sum(c2.cancelado) pendiente
		from
			(
				select
					s.id,
					s.name salePoint,
					sum(bd.quantity * bd.price) total,
					date_format(b.created_at, '%Y/%m/%d') fecha
				from 
					bill b, sale_point s, bill_detail bd
				where
					s.id = b.sale_point_id
				and b.id = bd.bill_id
				and b.created_at Between _from_date and _to_date
				group by b.id
			) c1,
			(
				select 
					s2.id,
					s2.name salePoint,
					pd2.payment cancelado,
					date_format(pd2.created_at, '%Y/%m/%d') fecha
				from 
					bill b2, 
					sale_point s2,
					pay_detail pd2
				where 
					b2.id = pd2.bill_id
				and s2.id = b2.sale_point_id
				and b2.created_at Between _from_date and _to_date        
				union 
				select
					s2.id,
					s2.name salePoint,
					0 cancelado,
					date_format(b2.created_at, '%Y/%m/%d') fecha
				from 
					bill b2, 
					sale_point s2,
					pay_detail pd2
				where 
					b2.payment_agreement_id = 2
				and s2.id = b2.sale_point_id
				and b2.created_at Between _from_date and _to_date
			) c2
		where 
			c1.id = c2.id
		and c1.fecha = c2.fecha
		group by c1.fecha, c1.salePoint, c2.cancelado;
    ELSE
		select 
			c1.salePoint salePoint,
			sum(c1.total) total,
			c1.fecha fecha,
			sum(c2.cancelado) cancelado,
			sum(c1.total) - sum(c2.cancelado) pendiente
		from
			(
				select
					s.id,
					s.name salePoint,
					sum(bd.quantity * bd.price) total,
					date_format(b.created_at, '%Y/%m/%d') fecha
				from 
					bill b, sale_point s, bill_detail bd
				where
					s.id = b.sale_point_id
				and s.id = _sale_point_id
				and b.id = bd.bill_id
				and b.created_at Between _from_date and _to_date
				group by b.id
			) c1,
			(
				select 
					s2.id,
					s2.name salePoint,
					pd2.payment cancelado,
					date_format(pd2.created_at, '%Y/%m/%d') fecha
				from 
					bill b2, 
					sale_point s2,
					pay_detail pd2
				where 
					b2.id = pd2.bill_id
				and s2.id = _sale_point_id
				and s2.id = b2.sale_point_id
				and b2.created_at Between _from_date and _to_date        
				union 
				select
					s2.id,
					s2.name salePoint,
					0 cancelado,
					date_format(b2.created_at, '%Y/%m/%d') fecha
				from 
					bill b2, 
					sale_point s2,
					pay_detail pd2
				where 
					b2.payment_agreement_id = 2
				and s2.id = b2.sale_point_id
                and s2.id = _sale_point_id
				and b2.created_at Between _from_date and _to_date
			) c2
		where 
			c1.id = c2.id
		and c1.fecha = c2.fecha
		group by c1.fecha, c1.salePoint, c2.cancelado;
    END IF;
END$$

DELIMITER ;