for(i = 0; i < array_length(global.gmhsNames); i++) { 
	draw_text(0, 15 * i, string(i+1) + ". " + global.gmhsNames[i]);
	draw_text(150, 15 * i, string(global.gmhsScores[i]));
}