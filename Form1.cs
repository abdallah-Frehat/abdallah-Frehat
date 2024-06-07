using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Windows_Games_c_
{
    public partial class Form1 : Form
    {
        private bool right, left, space;
        private readonly Random r = new Random();

        private int score;

        public Form1()
        {
            InitializeComponent();
        }

        private void game_result()
        {
            foreach (Control j in Controls)
            {
                if (j is PictureBox && (string)j.Tag == "bullete")
                {
                    foreach (Control i in Controls)
                    {
                        if (i is PictureBox && (string)i.Tag == "enemy")
                        {
                            if (j.Bounds.IntersectsWith(i.Bounds))
                            {
                                Controls.Remove(i);
                                score++;
                                label1.Text = score.ToString();
                                Controls.Remove(j);
                            }
                        }
                    }
                }
            }
            if (player.Bounds.IntersectsWith(enemy1.Bounds) || player.Bounds.IntersectsWith(enemy2.Bounds))
            {
                timer1.Stop();
                label3.Visible = true;
            }
        }

        private void stars_move()
        {
            foreach (Control n in Controls)
            {
                if (n is PictureBox && (string)n.Tag == "stars")
                {
                    n.Top += 10;
                    if (n.Top > 400)
                    {
                        n.Top = 0;
                    }
                }
            }
        }

        private void add_bullet()
        {
            PictureBox bullet = new PictureBox();
            bullet.SizeMode = PictureBoxSizeMode.StretchImage;
            bullet.Size = new Size(15, 15);
            bullet.Image = Properties.Resources.bullet;
            bullet.BackColor = Color.Transparent;
            bullet.Tag = "bullete";
            bullet.Left = player.Left + 15;
            bullet.Top = player.Top - 30;
            this.Controls.Add(bullet);
            bullet.BringToFront();
        }

        private void player_move()
        {
            if (right)
            {
                if (player.Left < 425)
                {
                    player.Left += 20;
                }
            }
            if (left)
            {
                if (player.Left > 10)
                {
                    player.Left -= 20;
                }
            }
        }

        private void enemymove()
        {
            int x, y;

            if (enemy1.Top >= 500)
            {
                x = r.Next(0, 300);
                enemy1.Location = new Point(x, 0);
            }
            if (enemy2.Top >= 500)
            {
                y = r.Next(0, 300);
                enemy2.Location = new Point(y, 0);
            }
            enemy1.Top += 15;
            enemy2.Top += 20;
        }

        private void bullet_move()
        {
            foreach (Control f in Controls)
            {
                if (f is PictureBox && (string)f.Tag == "bullete")
                {
                    f.Top -= 10;
                    if (f.Top < 0)
                    {
                        Controls.Remove(f);
                    }
                }
            }
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            stars_move();
            player_move();
            bullet_move();
            enemymove();
            game_result();
        }

        private void Form1_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Right)
            {
                right = true;
            }
            if (e.KeyCode == Keys.Left)
            {
                left = true;
            }
            if (e.KeyCode == Keys.Space)
            {
                space = true;
                add_bullet();
            }
        }

        private void Form1_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Right)
            {
                right = false;
            }
            if (e.KeyCode == Keys.Left)
            {
                left = false;
            }

        }
    }
}