create table bmjr_player(
	player_id integer not null,
	player_name varchar(64) not null,
	frequent tinyint not null,
	regular tinyint not null,
	constraint player_pk primary key(player_id),
	constraint player_name_uni unique(player_name)
);

create table bmjr_rcr_tournament(
	rcr_trmt_id integer not null,
	rcr_trmt_name varchar(64) not null,
	constraint rcr_trmt_pk primary key(rcr_trmt_id),
	constraint rcr_trmt_name_uni unique(rcr_trmt_name)
);

create table bmjr_rcr_game_id(
	rgi_id integer not null,
	rgi_date date not null,
	rgi_trmt_id integer not null,
	rgi_nb_players integer not null,
	rgi_nb_rounds integer not null,
	constraint rgi_pk primary key(rgi_id),
	constraint rgi_tid_fk foreign key(rgi_trmt_id) references bmjr_rcr_tournament(rcr_trmt_id) on delete cascade on update restrict
);

create table bmjr_rcr_game_score(
	rgs_game_id integer not null,
	rgs_player_id integer not null,
	rgs_ranking integer not null,
	rgs_game_score integer not null,
	rgs_uma_score integer not null,
	rgs_final_score integer not null,
	constraint rgs_pk primary key(rgs_game_id, rgs_player_id),
	constraint rgs_id_fk foreign key(rgs_game_id) references bmjr_rcr_game_id(rgi_id) on delete cascade on update restrict,
	constraint rgs_player_id_fk foreign key(rgs_player_id) references bmjr_player(player_id) on delete restrict on update restrict
);

create table bmjr_mcr_tournament(
	mcr_trmt_id integer not null,
	mcr_trmt_name varchar(64) not null,
	constraint mcr_trmt_pk primary key(mcr_trmt_id),
	constraint mcr_trmt_name_uni unique(mcr_trmt_name)
);

create table bmjr_mcr_game_id(
	mgi_id integer not null,
	mgi_date date not null,
	mgi_trmt_id integer not null,
	constraint mgi_pk primary key(mgi_id),
	constraint mgi_tid_fk foreign key(mgi_trmt_id) references bmjr_mcr_tournament(mcr_trmt_id) on delete cascade on update restrict
);

create table bmjr_mcr_game_score(
	mgs_game_id integer not null,
	mgs_player_id integer not null,
	mgs_ranking integer not null,
	mgs_game_score integer not null,
	mgs_final_score integer not null,
	constraint mgs_pk primary key(mgs_game_id, mgs_player_id),
	constraint mgs_id_fk foreign key(mgs_game_id) references bmjr_mcr_game_id(mgi_id) on delete cascade on update restrict,
	constraint mgs_player_id_fk foreign key(mgs_player_id) references bmjr_player(player_id) on delete restrict on update restrict
);
