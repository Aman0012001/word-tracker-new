import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { ContentLoaderComponent } from '../content-loader/content-loader.component';

@Component({
  selector: 'app-plan-detail',
  standalone: true,
  imports: [CommonModule, RouterLink, FormsModule, ContentLoaderComponent],
  templateUrl: './plan-detail.component.html',
  styleUrls: ['./plan-detail.component.scss']
})
export class PlanDetailComponent implements OnInit {
  plan: any;
  loading = true;

  constructor(
    private route: ActivatedRoute,
    private http: HttpClient
  ) { }

  ngOnInit() {
    const id = this.route.snapshot.paramMap.get('id');
    this.loadPlan(id || '1'); // Mock load
  }

  loadPlan(id: string) {
    this.loading = true;
    this.http.get<any>('http://localhost:8000/api/get_plan_full_details.php?id=' + id)
      .subscribe({
        next: (data) => {
          this.plan = data;
          this.loading = false;
          console.log('Plan Details Loaded:', this.plan);
        },
        error: (err) => {
          console.error('Failed to load plan', err);
          alert('Error loading plan details');
          this.loading = false;
        }
      });
  }

  updateProgress(day: any, newValue: any) {
    const val = parseInt(newValue, 10);
    if (isNaN(val)) return;

    // Call API to update day
    this.http.post('http://localhost:8000/api/add_progress.php', {
      plan_id: this.plan.id,
      date: day.date,
      count: val
    }).subscribe({
      next: (res: any) => {
        if (res.success) {
          // Reload to refresh stats/progress
          this.loadPlan(this.plan.id);
        }
      },
      error: (e) => console.error(e)
    });
  }
}
