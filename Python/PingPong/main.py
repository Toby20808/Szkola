import pygame
import random
import time

pygame.display.set_caption("Ping Pong")

def draw_text(screen, text, font, color, position):
    label = font.render(text, True, color)
    screen.blit(label, (position[0] - label.get_width() // 2, position[1] - label.get_height() // 2))
    return label.get_rect(center=position)

def main():
    pygame.init()
    w, h = 1280, 740
    screen = pygame.display.set_mode((w, h))
    clock = pygame.time.Clock()
    font = pygame.font.Font(None, 74)
    small_font = pygame.font.Font(None, 50)

    ball_speedup_image = pygame.transform.scale(pygame.image.load("images/ball_speedup.png").convert_alpha(), (60, 60))
    ghost_ball_image = pygame.transform.scale(pygame.image.load("images/ghost_ball.png").convert_alpha(), (60, 60))
    ball_sizeup_image = pygame.transform.scale(pygame.image.load("images/ball_sizeup.png").convert_alpha(), (60, 60))
    middle_wall_image = pygame.transform.scale(pygame.image.load("images/middle_wall.png").convert_alpha(), (60, 60))
    paddle_sizeup_image = pygame.transform.scale(pygame.image.load("images/todoodbijania_sizeup.png").convert_alpha(), (60, 60))

    selected_mode = None
    showing_info = False

    while selected_mode is None:
        screen.fill((0, 0, 0))

        if showing_info:
            draw_text(screen, "Informacje o Power-Upach", font, (255, 255, 0), (w // 2, 80))
            info_list = [
                ("Przyśpieszenie Piłki", "Piłka przyspiesza i zostawia ślad przez 5 sek.", ball_speedup_image),
                ("Powiększenie Piłki", "Piłka staje się większa na 5 sek.", ball_sizeup_image),
                ("Mur", "Na środku pojawia się ściana na 5 sek.", middle_wall_image),
                ("Większa Paletka", "Ostatni odbijający gracz dostaje większą paletkę na 5 sek.", paddle_sizeup_image),
                ("Piłka Duch", "Szara piłka porusza się niezależnie po ekranie.", ghost_ball_image)
            ]
            for i, (name, desc, icon) in enumerate(info_list):
                y = 150 + i * 100
                screen.blit(icon, (50, y))
                draw_text(screen, name, small_font, (255, 255, 255), (150, y + 70))
                draw_text(screen, desc, small_font, (200, 200, 200), (750, y + 20))
            draw_text(screen, "Kliknij myszką lub naciśnij klawisz, by wrócić", small_font, (180, 180, 180), (w // 2, h - 50))

            for event in pygame.event.get():
                if event.type == pygame.QUIT:
                    pygame.quit()
                    return
                elif event.type in (pygame.KEYDOWN, pygame.MOUSEBUTTONDOWN):
                    showing_info = False

        else:
            draw_text(screen, "Wybierz tryb gry", font, (255, 255, 255), (w // 2, h // 4))
            pvp_rect = draw_text(screen, "1. Gracz vs Gracz", small_font, (255, 255, 255), (w // 2, h // 2 - 60))
            bot_rect = draw_text(screen, "2. Gracz vs Bot", small_font, (255, 255, 255), (w // 2, h // 2 + 10))
            info_rect = draw_text(screen, "3. Informacje o Power-Upach", small_font, (100, 255, 100), (w // 2, h // 2 + 80))

            for event in pygame.event.get():
                if event.type == pygame.QUIT:
                    pygame.quit()
                    return
                elif event.type == pygame.MOUSEBUTTONDOWN:
                    if pvp_rect.collidepoint(event.pos):
                        selected_mode = "PVP"
                    elif bot_rect.collidepoint(event.pos):
                        selected_mode = "BOT"
                    elif info_rect.collidepoint(event.pos):
                        showing_info = True

        pygame.display.flip()
        clock.tick(30)

    paddle_width, paddle_height = 20, 100
    ball_size = 20

    player1 = pygame.Rect(50, h // 2 - paddle_height // 2, paddle_width, paddle_height)
    player2 = pygame.Rect(w - 70, h // 2 - paddle_height // 2, paddle_width, paddle_height)
    ball = pygame.Rect(w // 2, h // 2, ball_size, ball_size)

    ball_speed = [random.choice([-6, 6]), random.choice([-6, 6])]
    speed_increment = 0.5
    player1_speed = 0
    player2_speed = 0
    player_speed_value = 10

    score1, score2 = 0, 0
    game_time = 90
    start_ticks = pygame.time.get_ticks()

    powerup_timer = 0
    powerup_rect = None
    current_powerup = None
    last_hit_by = None
    bigger_paddle_timer = 0
    wall_active = False
    wall_timer = 0
    wall_rect = pygame.Rect(w // 2 - 5, 100, 10, h - 200)

    bigger_paddle_duration = 5
    player1_normal_height = paddle_height
    player2_normal_height = paddle_height

    ball_normal_size = ball_size
    ball_size_boost_duration = 0

    ghost_ball_active = False
    ghost_ball_rect = pygame.Rect(0, 0, ball_size, ball_size)
    ghost_ball_speed = [random.choice([-6, 6]), random.choice([-6, 6])]

    ball_trail = []
    max_trail_length = 15
    boost_active = False
    boost_end_time = 0

    bounce_flashes = []

    running = True
    while running:
        screen.fill((0, 0, 0))
        elapsed_time = (pygame.time.get_ticks() - start_ticks) // 1000
        if elapsed_time >= game_time:
            running = False
            break

        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                running = False
            if event.type == pygame.KEYDOWN:
                if event.key == pygame.K_w:
                    player1_speed = -player_speed_value
                if event.key == pygame.K_s:
                    player1_speed = player_speed_value
                if selected_mode == "PVP":
                    if event.key == pygame.K_UP:
                        player2_speed = -player_speed_value
                    if event.key == pygame.K_DOWN:
                        player2_speed = player_speed_value
            if event.type == pygame.KEYUP:
                if event.key in [pygame.K_w, pygame.K_s]:
                    player1_speed = 0
                if selected_mode == "PVP" and event.key in [pygame.K_UP, pygame.K_DOWN]:
                    player2_speed = 0

        player1.y += player1_speed
        player1.y = max(0, min(h - player1.height, player1.y))

        if selected_mode == "PVP":
            player2.y += player2_speed
        else:
            if ball.centery > player2.centery + 10:
                player2.y += player_speed_value - 2
            elif ball.centery < player2.centery - 10:
                player2.y -= player_speed_value - 2
        player2.y = max(0, min(h - player2.height, player2.y))

        ball.x += ball_speed[0]
        ball.y += ball_speed[1]

        if ball.top <= 0 or ball.bottom >= h:
            ball_speed[1] = -ball_speed[1]
            ball_speed[1] += speed_increment if ball_speed[1] > 0 else -speed_increment
            bounce_flashes.append((ball.center, pygame.time.get_ticks()))

        if ball.colliderect(player1) or ball.colliderect(player2):
            ball_speed[0] = -ball_speed[0]
            ball_speed[0] += speed_increment if ball_speed[0] > 0 else -speed_increment
            last_hit_by = "player1" if ball.colliderect(player1) else "player2"
            bounce_flashes.append((ball.center, pygame.time.get_ticks()))

        if wall_active and ball.colliderect(wall_rect):
            ball_speed[0] = -ball_speed[0]
            bounce_flashes.append((ball.center, pygame.time.get_ticks()))

        if ball.left <= 0:
            score2 += 1
            ball.x, ball.y = w // 2, h // 2
            ball_speed = [random.choice([-6, 8]), random.choice([-6, 8])]
        if ball.right >= w:
            score1 += 1
            ball.x, ball.y = w // 2, h // 2
            ball_speed = [random.choice([-6, 8]), random.choice([-6, 8])]

        if time.time() - powerup_timer > random.randint(10, 20):
            powerup_timer = time.time()
            kind = random.choice(["bigger", "wall", "boost", "ghost", "ball_size"])
            x = random.randint(w // 4, 3 * w // 4)
            y = random.randint(100, h - 100)
            powerup_rect = pygame.Rect(x, y, 60, 60)
            current_powerup = kind

        if powerup_rect:
            if current_powerup == "boost":
                screen.blit(ball_speedup_image, powerup_rect)
            elif current_powerup == "ball_size":
                screen.blit(ball_sizeup_image, powerup_rect)
            elif current_powerup == "wall":
                screen.blit(middle_wall_image, powerup_rect)
            elif current_powerup == "bigger":
                screen.blit(paddle_sizeup_image, powerup_rect)
            elif current_powerup == "ghost":
                screen.blit(ghost_ball_image, powerup_rect)
            if ball.colliderect(powerup_rect):
                if current_powerup == "bigger":
                    if last_hit_by == "player1":
                        player1.height = int(player1.height * 1.5)
                        bigger_paddle_timer = time.time()
                    elif last_hit_by == "player2":
                        player2.height = int(player2.height * 1.5)
                        bigger_paddle_timer = time.time()
                elif current_powerup == "wall":
                    wall_active = True
                    wall_timer = time.time()
                    powerup_rect = None
                elif current_powerup == "boost":
                    ball_speed = [speed * 1.5 for speed in ball_speed]
                    boost_active = True
                    boost_end_time = time.time() + 5
                elif current_powerup == "ghost":
                    ghost_ball_active = True
                    ghost_ball_rect.x, ghost_ball_rect.y = powerup_rect.x, powerup_rect.y
                    ghost_ball_speed = [random.choice([-6, 6]), random.choice([-6, 6])]
                elif current_powerup == "ball_size":
                    ball_size *= 1.5
                    ball_size_boost_duration = 5
                powerup_rect = None

        if wall_active:
            pygame.draw.rect(screen, (100, 100, 255), wall_rect)
            if time.time() - wall_timer > 5:
                wall_active = False

        if time.time() - bigger_paddle_timer > bigger_paddle_duration:
            if player1.height != player1_normal_height:
                player1.height = player1_normal_height
            if player2.height != player2_normal_height:
                player2.height = player2_normal_height

        if ball_size_boost_duration > 0:
            ball_size_boost_duration -= 1
            ball.width = ball.height = int(ball_size * 1.5)
        else:
            ball.width = ball.height = int(ball_size)

        if boost_active:
            ball_trail.append((ball.centerx, ball.centery))
            if len(ball_trail) > max_trail_length:
                ball_trail.pop(0)
        else:
            ball_trail.clear()

        for i, pos in enumerate(ball_trail):
            alpha = int(255 * (i + 1) / max_trail_length)
            trail_surf = pygame.Surface((ball.width, ball.height), pygame.SRCALPHA)
            trail_surf.fill((255, 140, 0, alpha))
            screen.blit(trail_surf, (pos[0] - ball.width // 2, pos[1] - ball.height // 2))

        if boost_active and time.time() > boost_end_time:
            boost_active = False

        if ghost_ball_active:
            pygame.draw.ellipse(screen, (169, 169, 169), ghost_ball_rect)
            ghost_ball_rect.x += ghost_ball_speed[0]
            ghost_ball_rect.y += ghost_ball_speed[1]
            if ghost_ball_rect.top <= 0 or ghost_ball_rect.bottom >= h:
                ghost_ball_speed[1] = -ghost_ball_speed[1]
            if ghost_ball_rect.left <= 0 or ghost_ball_rect.right >= w:
                ghost_ball_speed[0] = -ghost_ball_speed[0]

        now = pygame.time.get_ticks()
        for pos, start_time in bounce_flashes[:]:
            elapsed = now - start_time
            duration = 200

            if elapsed < duration:
                alpha = max(0, 255 - int((elapsed / duration) * 255))
                radius = 25
                flash_surf = pygame.Surface((radius * 2, radius * 2), pygame.SRCALPHA)
                pygame.draw.circle(flash_surf, (255, 255, 0, alpha), (radius, radius), radius)
                screen.blit(flash_surf, (pos[0] - radius, pos[1] - radius))
            else:
                bounce_flashes.remove((pos, start_time))


        pygame.draw.rect(screen, (255, 255, 255), player1)
        pygame.draw.rect(screen, (255, 255, 255), player2)
        pygame.draw.ellipse(screen, (255, 255, 255), ball)
        pygame.draw.aaline(screen, (255, 255, 255), (w // 2, 0), (w // 2, h))

        score_text = font.render(f"{score1} - {score2}", True, (255, 255, 255))
        screen.blit(score_text, (w // 2 - score_text.get_width() // 2, 20))

        pygame.display.flip()
        clock.tick(60)

    screen.fill((0, 0, 0))
    if score1 > score2:
        winner = "Gracz 1 wygrywa"
    elif score2 > score1:
        winner = "Gracz 2 wygrywa"
    else:
        winner = "Remis"
    end_text = font.render(f"Koniec gry! {winner}", True, (255, 255, 255))
    screen.blit(end_text, (w // 2 - end_text.get_width() // 2, h // 2))
    pygame.display.flip()
    pygame.time.delay(3000)
    pygame.quit()

if __name__ == "__main__":
    main()
