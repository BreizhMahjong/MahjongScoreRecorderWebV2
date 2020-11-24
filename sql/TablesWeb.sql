create table bmjr_player(
	player_id smallint unsigned not null,
	player_name varchar(64) not null,
	player_real_name varchar(64) not null,
	frequent tinyint unsigned not null,
	regular tinyint unsigned not null,
	license varchar(10),
	constraint player_pk primary key(player_id),
	constraint player_name_uni unique(player_name)
);

create table bmjr_rcr_tournament(
	rcr_trmt_id smallint unsigned not null,
	rcr_trmt_name varchar(64) not null,
	constraint rcr_trmt_pk primary key(rcr_trmt_id),
	constraint rcr_trmt_name_uni unique(rcr_trmt_name)
);

create table bmjr_rcr_game_id(
	rgi_id bigint unsigned not null,
	rgi_date date not null,
	rgi_trmt_id smallint unsigned not null,
	rgi_nb_players tinyint unsigned not null,
	rgi_nb_rounds tinyint unsigned not null,
	constraint rgi_pk primary key(rgi_id),
	constraint rgi_tid_fk foreign key(rgi_trmt_id) references bmjr_rcr_tournament(rcr_trmt_id) on delete cascade on update restrict
);

create table bmjr_rcr_game_score(
	rgs_game_id bigint unsigned not null,
	rgs_player_id smallint unsigned not null,
	rgs_ranking tinyint unsigned not null,
	rgs_game_score integer not null,
	rgs_uma_score integer not null,
	rgs_final_score integer not null,
	constraint rgs_pk primary key(rgs_game_id, rgs_player_id),
	constraint rgs_id_fk foreign key(rgs_game_id) references bmjr_rcr_game_id(rgi_id) on delete cascade on update restrict,
	constraint rgs_player_id_fk foreign key(rgs_player_id) references bmjr_player(player_id) on delete restrict on update restrict
);
