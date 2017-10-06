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
                ctotal.id,
                ctotal.salePoint,
                ctotal.total total,
                cpagado.total cancelado,
                ctotal.total - cpagado.total pendiente,
                ctotal.fecha 
            from
            (
                select 
                    s.id id,
                    s.name salePoint,
                    sum(ifnull(bd.quantity * bd.price, 0)) total,
                    date_format(b.created_at, '%d/%m/%Y') fecha
                from 
                    sale_point s
                left join bill b on s.id = b.sale_point_id
                left join bill_detail bd on b.id = bd.bill_id
                where 
                    b.created_at between _from_date and _to_date
                group by s.id, date_format(b.created_at, '%d/%m/%Y')
            ) ctotal,
            (
                select 
                    s2.id id,
                    s2.name salePoint,
                    sum(ifnull(pd.payment, 0)) total,
                    date_format(b2.created_at, '%d/%m/%Y') fecha
                from 
                    sale_point s2
                left join bill b2 on s2.id = b2.sale_point_id
                left join pay_detail pd on b2.id = pd.bill_id
                where 
                    b2.created_at between _from_date and _to_date
                group by s2.id, date_format(b2.created_at, '%d/%m/%Y')
            )cpagado
        where 
            cpagado.id = ctotal.id
        and cpagado.fecha = ctotal.fecha
        and cpagado.salePoint = ctotal.salePoint;
    ELSE
        select 
            ctotal.id,
            ctotal.salePoint,
            ctotal.total total,
            cpagado.total cancelado,
            ctotal.total - cpagado.total pendiente,
            ctotal.fecha 
        from
            (
                select 
                    s.id id,
                    s.name salePoint,
                    sum(ifnull(bd.quantity * bd.price, 0)) total,
                    date_format(b.created_at, '%d/%m/%Y') fecha
                from 
                    sale_point s
                left join bill b on s.id = b.sale_point_id
                left join bill_detail bd on b.id = bd.bill_id
                where 
                    s.id = _sale_point_id
                    and b.created_at between _from_date and _to_date
                group by s.id, date_format(b.created_at, '%d/%m/%Y')
            ) ctotal,
            (
                select 
                    s2.id id,
                    s2.name salePoint,
                    sum(ifnull(pd.payment, 0)) total,
                    date_format(b2.created_at, '%d/%m/%Y') fecha
                from 
                    sale_point s2
                left join bill b2 on s2.id = b2.sale_point_id
                left join pay_detail pd on b2.id = pd.bill_id
                where 
                    s2.id = _sale_point_id
                    and b2.created_at between _from_date and _to_date
                group by s2.id, date_format(b2.created_at, '%d/%m/%Y')
            )cpagado
        where 
            cpagado.id = ctotal.id
        and cpagado.fecha = ctotal.fecha
        and cpagado.salePoint = ctotal.salePoint;
    END IF;
END$$
DELIMITER ;