import { Component, Input, OnChanges, SimpleChanges } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

@Component({
    selector: 'app-plan-editor-progress',
    standalone: true,
    imports: [CommonModule, FormsModule],
    templateUrl: './plan-editor-progress.component.html',
    styleUrls: ['./plan-editor-progress.component.scss']
})
export class PlanEditorProgressComponent implements OnChanges {
    @Input() planId: number | null = null;

    loading = false;
    dailyData: any[] = [];
    error = '';
    successMessage = '';

    constructor(private http: HttpClient) { }

    ngOnChanges(changes: SimpleChanges) {
        if (this.planId && changes['planId']) {
            this.loadProgress();
        }
    }

    loadProgress() {
        if (!this.planId) return;
        this.loading = true;
        this.http.get(`http://localhost:8000/api/get_stats.php?plan_id=${this.planId}`)
            .subscribe({
                next: (res: any) => {
                    if (res.success) {
                        this.dailyData = res.daily_data;
                    }
                    this.loading = false;
                },
                error: (err) => {
                    console.error(err);
                    this.loading = false;
                }
            });
    }

    updateProgress(day: any) {
        if (!this.planId) return;

        this.http.post('http://localhost:8000/api/add_progress.php', {
            plan_id: this.planId,
            date: day.date,
            count: day.logged
        }).subscribe({
            next: (res: any) => {
                this.successMessage = `Saved for ${day.date}`;
                setTimeout(() => this.successMessage = '', 3000);
                // define specific logic if needed, e.g. reload stats
            },
            error: (err) => {
                console.error(err);
                this.error = 'Failed to save';
                setTimeout(() => this.error = '', 3000);
            }
        });
    }

    isToday(dateStr: string): boolean {
        const today = new Date().toISOString().split('T')[0];
        return dateStr === today;
    }
}
