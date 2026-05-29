-- Active: 1774887976769@@127.0.0.1@3306@futbol
use futbol;

SELECT * FROM games;

SELECT * FROM teams;

SELECT id, 
    short_name, 
        (SELECT COUNT(*)
            FROM games g
            WHERE g.home_team_id = t.id AND g.home_goals > g.away_goals
        ) * 3 +
        (SELECT COUNT(*)
            FROM games g
            WHERE g.away_team_id = t.id AND g.away_goals > g.home_goals
        ) * 3 +
        (SELECT COUNT(*)
            FROM games g
            WHERE (g.home_team_id = t.id OR g.away_team_id = t.id) AND g.away_goals = g.home_goals
        ) AS points
    FROM teams t
ORDER BY points DESC
LIMIT 3;