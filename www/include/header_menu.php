
<div id="sidebar">
	<ul>

		<?if($_SESSION["admin_permission"][ch_c00]=="y"){?>
		<li class="active"><a href="/01_fdata/1_finput/regist.php"><i class="icon icon-home"></i> <span>원데이터</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_c11]=="y"){?><li><a href="/01_fdata/1_finput/regist.php"><i class="icon icon-signal"></i>최초입력</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_c21]=="y"){?><li><a href="/01_fdata/2_update"><i class="icon icon-signal"></i>수정/기간입력</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_c31]=="y"){?><li><a href="/01_fdata/3_infoinput"><i class="icon icon-signal"></i>실거래정보입력</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_c41]=="y"){?><li><a href="/01_fdata/4_docinfo"><i class="icon icon-signal"></i>미비서류안내</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_c51]=="y"){?><li><a href="/01_fdata/5_openbus"><i class="icon icon-signal"></i>오픈버스용</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_d00]=="y"){?>
		<li class="active"><a href="/02_cost/1_fullpay"><i class="icon icon-home"></i> <span>비용관련</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_d11]=="y"){?><li><a href="/02_cost/1_fullpay"><i class="icon icon-signal"></i>완납증명서입력</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_d21]=="y"){?><li><a href="/02_cost/2_taxreport"><i class="icon icon-signal"></i>취득세신고</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_d31]=="y"){?><li><a href="/02_cost/3_vaccount"><i class="icon icon-signal"></i>가상계좌/수납</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_d41]=="y"){?><li><a href="/02_cost/4_costinfo"><i class="icon icon-signal"></i>비용안내</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_d51]=="y"){?><li><a href="/02_cost/5_memupload"><i class="icon icon-signal"></i>회원데이터업로드</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_d61]=="y"){?><li><a href="/02_cost/6_payset"><i class="icon icon-signal"></i>보수료설정</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_e00]=="y"){?>
		<li class="active"><a href="/03_utilbill/1_bondcommove"><i class="icon icon-home"></i> <span>공과금산정납부</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_e11]=="y"){?><li><a href="/03_utilbill/1_bondcommove"><i class="icon icon-signal"></i>채권산정(이전채권)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_e21]=="y"){?><li><a href="/03_utilbill/2_bondcomset"><i class="icon icon-signal"></i>채권산정(설정채권)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_e31]=="y"){?><li><a href="/03_utilbill/3_etc"><i class="icon icon-signal"></i>기타공과금산정</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_e41]=="y"){?><li><a href="/03_utilbill/4_taxpay"><i class="icon icon-signal"></i>취득세납부</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_e51]=="y"){?><li><a href="/03_utilbill/5_bondpubmove"><i class="icon icon-signal"></i>채권발행(이전채권)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_e61]=="y"){?><li><a href="/03_utilbill/6_bondpubset"><i class="icon icon-signal"></i>채권발행(설정채권)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_e71]=="y"){?><li><a href="/03_utilbill/7_taxsetpay"><i class="icon icon-signal"></i>설정등록세신고납부</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_e81]=="y"){?><li><a href="/03_utilbill/8_cansetpay"><i class="icon icon-signal"></i>말소변경등록세신고납부</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_f00]=="y"){?>
		<li class="active"><a href="/04_regteam/1_regteamlist"><i class="icon icon-home"></i> <span>등기관리팀</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_f11]=="y"){?><li><a href="/04_regteam/1_regteamlist"><i class="icon icon-signal"></i>등기관리팀리스트</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_f21]=="y"){?><li><a href="/04_regteam/2_regreport"><i class="icon icon-signal"></i>등기신청서출력</a></li><?}?>
			</ul>
		</li>
		<?}?>

		<?if($_SESSION["admin_permission"][ch_100]=="y"){?>
		<li class="active"><a href="/1_junib"><i class="icon icon-home"></i> <span>전입세대열람</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_111]=="y"){?><li><a href="/1_junib"><i class="icon icon-signal"></i>전입세대열람 조회</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_121]=="y"){?><li><a href="/1_junib/regist_list.php"><i class="icon icon-signal"></i>전입세대열람 등록</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_200]=="y"){?>
		<li><a href="/2_custom/1_search"><i class="icon icon-signal"></i> <span>고객지원팀</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_211]=="y"){?><li><a href="/2_custom/1_search">고객지원팀 조회</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_231]=="y"){?><li><a href="/2_custom/2_ips">필증수령입력(이전)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_251]=="y"){?><li><a href="/2_custom/4_ipj">필증전달입력(이전)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_271]=="y"){?><li><a href="/2_custom/6_ijw">정산완료입력(이전)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_291]=="y"){?><li><a href="/2_custom/8_ipb">필증배포입력(이전)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_241]=="y"){?><li><a href="/2_custom/3_sps">필증수령입력(설정)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_261]=="y"){?><li><a href="/2_custom/5_spj">필증전달입력(설정)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_281]=="y"){?><li><a href="/2_custom/7_sjw">정산완료입력(설정)</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_2a1]=="y"){?><li><a href="/2_custom/9_spb">필증배포입력(설정)</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_500]=="y"){?>
		<li><a href="/5_form/ijun.php"><i class="icon icon-inbox"></i> <span>양식관리</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_511]=="y"){?><li><a href="/5_form/ijun.php">이전양식</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_521]=="y"){?><li><a href="/5_form/suljung.php">설정양식</a></li><?}?>
				<!--<li><a href="/5_form/gukto.php">국토교통부</a></li>//-->
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_600]=="y"){?>
		<li><a href="/6_sugum"><i class="icon icon-th"></i> <span>수금관리</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_611]=="y"){?><li><a href="/6_sugum/">수금관리</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_700]=="y"){?>
		<li><a href="/7_refund"><i class="icon icon-fullscreen"></i> <span>환불금관리</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_711]=="y"){?><li><a href="/7_refund/">환불금조회</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_721]=="y"){?><li><a href="/7_refund/refund_account.html">환불계좌등록</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_731]=="y"){?><li><a href="/7_refund/refund_end.html">환불완료일등록</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_800]=="y"){?>
		<li class="submenu"> <a href="/8_erp"><i class="icon icon-th-list"></i> <span>ERP기장</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_811]=="y"){?><li><a href="/8_erp/">세금계산서발행내역</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_821]=="y"){?><li><a href="/8_erp/index_card.html">카드승인내역</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_831]=="y"){?><li><a href="/8_erp/index_hg.html">현금영수증발행내역</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_900]=="y"){?>
		<li><a href="/9_basic/91_info/info.php"><i class="icon icon-tint"></i> <span>기본정보</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_911]=="y"){?><li><a href="/9_basic/91_info/info.php">태율정보관리</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_921]=="y"){?><li><a href="/9_basic/92_account">태율계좌관리</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_931]=="y"){?><li><a href="/9_basic/93_jungsan_report">정산보고서조회</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_941]=="y"){?><li><a href="/9_basic/94_hyunjang_info">현장상세정보</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_951]=="y"){?><li><a href="/9_basic/95_bank_info">은행기본정보</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_961]=="y"){?><li><a href="/9_basic/96_bank_jijum">은행지점정보</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_971]=="y"){?><li><a href="/9_basic/97_bank_basic_rate">은행기본비용설정</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_981]=="y"){?><li><a href="/9_basic/98_bank_jijum_rate">은행지점비용설정</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_991]=="y"){?><li><a href="/9_basic/99_sosok">소속설정</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_9a1]=="y"){?><li><a href="/9_basic/991_hol_date">공휴일설정</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_300]=="y"){?>
		<li><a href="/3_data"><i class="icon icon-pencil"></i> <span>데이터관리</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_311]=="y"){?><li><a href="/3_data">엑셀업로드</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_321]=="y"){?><li><a href="/3_data/backup.php">엑셀백업</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_331]=="y"){?><li><a href="/3_data/data_delete.php">데이터삭제</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_341]=="y"){?><li><a href="/3_data/accident.php">사건부</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_a00]=="y"){?>
		<li class="submenu"> <a href="/a_account"><i class="icon icon-plus-sign"></i> <span>계정관리</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_a11]=="y"){?><li><a href="/a_account">계정목록</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_a21]=="y"){?><li><a href="/a_account/regist.php">계정등록</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_a31]=="y"){?><li><a href="/a_account/history">수정/삭제이력</a></li><?}?>
			</ul>
		</li>
		<?}?>
		<?if($_SESSION["admin_permission"][ch_b00]=="y"){?>
		<li class="submenu"> <a href="/b_bbs"><i class="icon icon-file"></i> <span>게시판</span></a>
			<ul>
				<?if($_SESSION["admin_permission"][ch_b11]=="y"){?><li><a href="/b_bbs">매뉴얼</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_b21]=="y"){?><li><a href="/b_bbs2">자료실</a></li><?}?>
				<?if($_SESSION["admin_permission"][ch_b31]=="y"){?><li><a href="/b_bbs3">FAQ</a></li><?}?>
			</ul>
		</li>
		<?}?>
	</ul>
</div>